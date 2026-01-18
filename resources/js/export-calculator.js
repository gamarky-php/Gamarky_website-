// resources/js/export-calculator.js
// Alpine.js component for Export Cost Calculator
// Alpine.js loaded globally via app.js
console.info('export-calculator page script loaded');

/**
 * Export Calculator Component
 * يدير حاسبة تكاليف التصدير بشكل ديناميكي
 */
window.ExportCalc = () => ({
  // بيانات النموذج
  form: {
    incoterm: 'FOB',
    currency: 'USD',
    fx_rate: 1,
    method: 'sea',
    etd: '',
    origin_country: '',
    pol: '',
    pod: '',
    container_type: ''
  },

  // الأعمدة (البنود)
  columns: [
    { title: 'بند 1' }
  ],

  // صفوف الجدول
  rows: [
    { key: 'manufacturing', label: 'تكلفة التصنيع', input: true, category: 'manufacturing' },
    { key: 'packing', label: 'التعبئة والتغليف', input: true, category: 'packing' },
    { key: 'local_clearance', label: 'رسوم التخليص المحلي', input: true, category: 'local_clearance', incoterms: ['FOB', 'CFR', 'CIF'] },
    { key: 'port_fees', label: 'رسوم الميناء', input: true, category: 'port_fees', incoterms: ['FOB', 'CFR', 'CIF'] },
    { key: 'local_trucking', label: 'نقل محلي إلى الميناء', input: true, category: 'local_trucking', incoterms: ['FOB', 'CFR', 'CIF'] },
    { key: 'freight', label: 'الشحن الدولي', input: true, category: 'freight', incoterms: ['CFR', 'CIF'] },
    { key: 'insurance', label: 'التأمين الدولي', input: true, category: 'insurance', incoterms: ['CIF'] },
    { key: 'bank', label: 'مصاريف بنكية', input: true, category: 'bank' },
    { key: 'docs', label: 'شهادات/توثيق', input: true, category: 'docs' },
    { key: 'extras', label: 'خدمات إضافية', input: true, category: 'extras' },
    { key: 'profit', label: 'هامش الربح', input: true, category: 'profit' },
    { key: 'final_price', label: 'سعر البيع النهائي', input: false, category: 'final_price' }
  ],

  // القيم - row.key -> [per column]
  values: {},

  // الإجماليات
  totals: {
    exw: 0,
    fob: 0,
    cfr: 0,
    cif: 0
  },

  // معرف العرض الحالي
  currentQuoteId: null,

  /**
   * التهيئة الأولية
   */
  init() {
    // تهيئة القيم لكل صف
    this.rows.forEach(row => {
      this.values[row.key] = this.columns.map(() => 0)
    })
    
    // تطبيق Incoterm الافتراضي
    this.applyIncoterm()
    
    // Watchers للتحديث الفوري
    this.$watch('form.incoterm', (value) => {
      this.applyIncoterm()
      this.recalc()
    })
    
    this.$watch('form.currency', () => {
      this.recalc()
    })
    
    this.$watch('form.fx_rate', () => {
      this.recalc()
    })
  },

  /**
   * إضافة عمود جديد
   */
  addColumn() {
    const newIndex = this.columns.length + 1
    this.columns.push({ title: `بند ${newIndex}` })
    
    // إضافة قيم للصفوف الموجودة
    Object.keys(this.values).forEach(key => {
      this.values[key].push(0)
    })
    
    this.recalc()
  },

  /**
   * حذف عمود
   */
  removeColumn(index) {
    if (this.columns.length <= 1) {
      alert('يجب أن يبقى بند واحد على الأقل')
      return
    }
    
    this.columns.splice(index, 1)
    Object.keys(this.values).forEach(key => {
      this.values[key].splice(index, 1)
    })
    
    this.recalc()
  },

  /**
   * تطبيق Incoterm - إظهار/إخفاء الصفوف
   */
  applyIncoterm() {
    const selectedIncoterm = this.form.incoterm
    
    this.rows.forEach(row => {
      if (row.incoterms) {
        // إخفاء الصف إذا لم يكن ضمن Incoterms المطلوبة
        row.hidden = !row.incoterms.includes(selectedIncoterm)
      } else {
        // إظهار الصف دائماً
        row.hidden = false
      }
    })
    
    this.recalc()
  },

  /**
   * الحصول على قيمة خلية
   */
  getValue(key, index) {
    // حساب السعر النهائي
    if (key === 'final_price') {
      return this.calculateFinalPrice(index)
    }
    
    return this.values[key]?.[index] || 0
  },

  /**
   * حساب السعر النهائي حسب Incoterm
   */
  calculateFinalPrice(index) {
    const v = (key) => (this.values[key]?.[index] || 0)
    
    // المكونات الأساسية (موجودة في كل Incoterms)
    let base = v('manufacturing') + 
               v('packing') + 
               v('bank') + 
               v('docs') + 
               v('extras')
    
    // إضافة المكونات حسب Incoterm
    const incoterm = this.form.incoterm
    
    if (['FOB', 'CFR', 'CIF'].includes(incoterm)) {
      base += v('local_clearance') + v('port_fees') + v('local_trucking')
    }
    
    if (['CFR', 'CIF'].includes(incoterm)) {
      base += v('freight')
    }
    
    if (incoterm === 'CIF') {
      base += v('insurance')
    }
    
    // إضافة هامش الربح
    const profit = v('profit')
    
    return base + profit
  },

  /**
   * حساب إجمالي EXW
   */
  calculateEXW(index) {
    const v = (key) => (this.values[key]?.[index] || 0)
    return v('manufacturing') + v('packing')
  },

  /**
   * حساب إجمالي FOB
   */
  calculateFOB(index) {
    const v = (key) => (this.values[key]?.[index] || 0)
    return this.calculateEXW(index) + 
           v('local_clearance') + 
           v('port_fees') + 
           v('local_trucking')
  },

  /**
   * حساب إجمالي CFR
   */
  calculateCFR(index) {
    const v = (key) => (this.values[key]?.[index] || 0)
    return this.calculateFOB(index) + v('freight')
  },

  /**
   * حساب إجمالي CIF
   */
  calculateCIF(index) {
    const v = (key) => (this.values[key]?.[index] || 0)
    return this.calculateCFR(index) + v('insurance')
  },

  /**
   * إعادة حساب الإجماليات
   */
  recalc() {
    this.totals.exw = 0
    this.totals.fob = 0
    this.totals.cfr = 0
    this.totals.cif = 0

    this.columns.forEach((col, idx) => {
      this.totals.exw += this.calculateEXW(idx)
      this.totals.fob += this.calculateFOB(idx)
      this.totals.cfr += this.calculateCFR(idx)
      this.totals.cif += this.calculateCIF(idx)
    })
  },

  /**
   * تنسيق الأرقام
   */
  format(number) {
    return new Intl.NumberFormat('ar-EG', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(number || 0)
  },

  /**
   * تلوين الربحية
   */
  profitClass(value) {
    if (value < 0) {
      return 'bg-black text-white px-2 py-1 rounded font-bold'
    }
    if (value < 100) {
      return 'text-red-700'
    }
    if (value < 500) {
      return 'text-orange-700'
    }
    if (value < 1000) {
      return 'text-blue-700'
    }
    return 'text-green-700 font-semibold'
  },

  /**
   * مسح جميع القيم
   */
  resetAll() {
    if (!confirm('هل تريد مسح جميع البيانات؟')) {
      return
    }
    
    Object.keys(this.values).forEach(key => {
      this.values[key] = this.values[key].map(() => 0)
    })
    
    this.recalc()
  },

  /**
   * حفظ الشحنة
   */
  async saveShipment() {
    // جمع بنود التكلفة
    const costs = []
    
    this.rows.forEach(row => {
      if (row.input && row.category && !row.hidden) {
        this.columns.forEach((col, idx) => {
          const amount = this.values[row.key]?.[idx] || 0
          if (amount > 0) {
            costs.push({
              line_name: row.label,
              category: row.category,
              col_index: idx + 1,
              amount: amount,
              currency: this.form.currency,
              meta: {
                column_title: col.title
              }
            })
          }
        })
      }
    })

    if (costs.length === 0) {
      alert('الرجاء إدخال بعض البيانات قبل الحفظ')
      return
    }

    const data = {
      ...this.form,
      costs: costs
    }

    try {
      const response = await fetch(window.saveShipmentUrl || '/export/calculator', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      })

      if (response.ok) {
        const result = await response.json()
        alert('تم حفظ الشحنة بنجاح')
        
        if (result.redirect) {
          window.location.href = result.redirect
        }
      } else {
        const error = await response.json()
        alert('حدث خطأ: ' + (error.message || 'خطأ غير معروف'))
      }
    } catch (error) {
      console.error('Error saving shipment:', error)
      alert('حدث خطأ في الاتصال بالخادم')
    }
  },

  /**
   * توليد عرض سعر من الشحنة المحفوظة
   */
  async generateQuote() {
    // التحقق من وجود بيانات
    const hasData = Object.values(this.values).some(arr => 
      arr.some(val => val > 0)
    )
    
    if (!hasData) {
      alert('الرجاء إدخال بعض البيانات قبل إنشاء العرض')
      return
    }

    // جمع بنود التكلفة
    const costs = []
    
    this.rows.forEach(row => {
      if (row.input && row.category && !row.hidden) {
        this.columns.forEach((col, idx) => {
          const amount = this.values[row.key]?.[idx] || 0
          if (amount > 0) {
            costs.push({
              line_name: row.label,
              category: row.category,
              col_index: idx + 1,
              amount: amount,
              currency: this.form.currency,
              meta: {
                column_title: col.title
              }
            })
          }
        })
      }
    })

    const shipmentData = {
      ...this.form,
      costs: costs
    }

    try {
      // الخطوة 1: حفظ الشحنة
      const saveResponse = await fetch(window.saveShipmentUrl || '/export/calculator', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Accept': 'application/json'
        },
        body: JSON.stringify(shipmentData)
      })

      if (!saveResponse.ok) {
        const error = await saveResponse.json()
        alert('حدث خطأ في حفظ الشحنة: ' + (error.message || 'خطأ غير معروف'))
        return
      }

      const shipmentResult = await saveResponse.json()
      const shipmentId = shipmentResult.shipment_id || shipmentResult.id

      if (!shipmentId) {
        alert('لم يتم الحصول على معرف الشحنة')
        return
      }

      // الخطوة 2: إنشاء عرض السعر
      const quoteData = {
        incoterm_final: this.form.incoterm,
        margin_pct: 10 // يمكن جعلها قابلة للتخصيص
      }

      const quoteResponse = await fetch(`/export/shipments/${shipmentId}/quotes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Accept': 'application/json'
        },
        body: JSON.stringify(quoteData)
      })

      if (!quoteResponse.ok) {
        const error = await quoteResponse.json()
        alert('حدث خطأ في إنشاء العرض: ' + (error.message || 'خطأ غير معروف'))
        return
      }

      const quoteResult = await quoteResponse.json()
      
      // التوجيه لصفحة العرض
      if (quoteResult.redirect) {
        window.location.href = quoteResult.redirect
      } else if (quoteResult.quote_id || quoteResult.id) {
        window.location.href = `/export/quotes/${quoteResult.quote_id || quoteResult.id}`
      } else {
        alert('تم إنشاء العرض بنجاح')
      }

    } catch (error) {
      console.error('Error generating quote:', error)
      alert('حدث خطأ في الاتصال بالخادم')
    }
  },

  /**
   * روابط PDF/Excel
   */
  get quotePdfUrl() {
    if (this.currentQuoteId) {
      return `/export/quotes/${this.currentQuoteId}/pdf`
    }
    return '/export/quotes/preview.pdf'
  },

  get quoteExcelUrl() {
    if (this.currentQuoteId) {
      return `/export/quotes/${this.currentQuoteId}/excel`
    }
    return '/export/quotes/preview.xlsx'
  }
})
