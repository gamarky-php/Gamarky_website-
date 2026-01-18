// Alpine.js loaded globally via app.js
console.info('mfg-calculator page script loaded');

/**
 * Manufacturing Cost Calculator - Alpine.js Component
 * حاسبة تكاليف التصنيع
 */
window.MfgCalc = () => ({
  // بيانات المنتجات من الـBlade (window.__products)
  products: window.__products || [],
  
  // نموذج المدخلات الرئيسية
  form: {
    product_id: null,
    batch: 100,
    scrap_pct: 0,
    currency: 'USD',
    fx_rate: 1,
    margin_pct: 20
  },
  
  // قوائم البيانات الديناميكية
  bom: [],   // Bill of Materials
  ops: [],   // Operations (Routing)
  ohs: [],   // Overhead Pools
  
  // النتائج المحسوبة
  total: {
    material: 0,
    operations: 0,
    overhead: 0,
    batch: 0,
    unit: 0,
    target_price: 0
  },
  
  currentRunId: null,
  
  /**
   * التهيئة الأولية
   */
  init() {
    this.addBom();
    this.addOp();
    this.addOh();
    this.recalc();
  },
  
  /**
   * إضافة صف BOM
   */
  addBom() {
    this.bom.push({
      material: '',
      uom: 'kg',
      qty: 0,
      price: 0,
      scrap: 0
    });
  },
  
  /**
   * حذف صف BOM
   */
  removeBom(i) {
    if (this.bom.length > 1) {
      this.bom.splice(i, 1);
      this.recalc();
    }
  },
  
  /**
   * إضافة عملية تشغيلية
   */
  addOp() {
    this.ops.push({
      operation: '',
      setup: 0,
      run: 0,
      labor: 0,
      machine: 0
    });
  },
  
  /**
   * حذف عملية تشغيلية
   */
  removeOp(i) {
    if (this.ops.length > 1) {
      this.ops.splice(i, 1);
      this.recalc();
    }
  },
  
  /**
   * إضافة مجمع تكاليف غير مباشرة
   */
  addOh() {
    this.ohs.push({
      name: '',
      basis: 'machine_hour',
      rate: 0
    });
  },
  
  /**
   * حذف مجمع تكاليف غير مباشرة
   */
  removeOh(i) {
    if (this.ohs.length > 1) {
      this.ohs.splice(i, 1);
      this.recalc();
    }
  },
  
  /**
   * حساب تكلفة مادة BOM واحدة
   */
  bomCost(i) {
    const it = this.bom[i];
    if (!it) return 0;
    const grossQty = (it.qty || 0) * (1 + (it.scrap || 0) / 100);
    return grossQty * (it.price || 0);
  },
  
  /**
   * حساب تكلفة عملية واحدة
   */
  opCost(i) {
    const op = this.ops[i];
    if (!op) return 0;
    const totalHours = (op.setup || 0) + (op.run || 0);
    const hourlyRate = (op.labor || 0) + (op.machine || 0);
    return totalHours * hourlyRate;
  },
  
  /**
   * حساب تكلفة overhead واحدة
   */
  ohCost(i) {
    const oh = this.ohs[i];
    if (!oh) return 0;
    
    const basis = oh.basis;
    const rate = oh.rate || 0;
    
    // حساب ساعات العمل والماكينة
    const laborHr = this.ops.reduce((sum, op) => sum + (op.setup || 0) + (op.run || 0), 0);
    const machHr = this.ops.reduce((sum, op) => sum + (op.setup || 0) + (op.run || 0), 0);
    
    // حساب تكلفة المواد
    const mats = this.bom.reduce((sum, _, idx) => sum + this.bomCost(idx), 0);
    
    if (basis === 'labor_hour') {
      return laborHr * rate;
    } else if (basis === 'machine_hour') {
      return machHr * rate;
    } else if (basis === 'material_pct') {
      return mats * (rate / 100);
    }
    
    return 0;
  },
  
  /**
   * إعادة حساب جميع التكاليف
   */
  recalc() {
    // تكلفة المواد
    const materialsCost = this.bom.reduce((sum, _, i) => sum + this.bomCost(i), 0);
    
    // تكلفة العمليات
    const operationsCost = this.ops.reduce((sum, _, i) => sum + this.opCost(i), 0);
    
    // التكاليف غير المباشرة
    const overheadCost = this.ohs.reduce((sum, _, i) => sum + this.ohCost(i), 0);
    
    // حساب الدفعة الفعلية بعد الفاقد
    const batchEffective = Math.max(1, (this.form.batch || 1) * (1 - (this.form.scrap_pct || 0) / 100));
    
    // إجمالي تكلفة الدفعة (مع سعر الصرف)
    const batchTotal = (materialsCost + operationsCost + overheadCost) * (this.form.fx_rate || 1);
    
    // تكلفة الوحدة
    const unitCost = batchTotal / batchEffective;
    
    // سعر البيع المستهدف
    const targetPrice = unitCost / (1 - (this.form.margin_pct || 0) / 100);
    
    this.total = {
      material: materialsCost,
      operations: operationsCost,
      overhead: overheadCost,
      batch: batchTotal,
      unit: unitCost,
      target_price: targetPrice
    };
  },
  
  /**
   * تنسيق الأرقام بالعربية
   */
  fmt(n) {
    return new Intl.NumberFormat('ar-EG', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(n || 0) + ' ' + this.form.currency;
  },
  
  /**
   * مسح جميع البيانات
   */
  resetAll() {
    if (confirm('هل تريد مسح جميع البيانات؟')) {
      this.bom = [];
      this.ops = [];
      this.ohs = [];
      this.form.batch = 100;
      this.form.scrap_pct = 0;
      this.form.margin_pct = 20;
      this.form.fx_rate = 1;
      this.currentRunId = null;
      this.init();
    }
  },
  
  /**
   * حفظ التشغيل في قاعدة البيانات
   */
  async saveRun() {
    const payload = {
      product_id: this.form.product_id,
      batch_size: this.form.batch,
      scrap_pct: this.form.scrap_pct,
      currency: this.form.currency,
      fx_rate: this.form.fx_rate,
      margin_pct: this.form.margin_pct,
      bom_items: this.bom.map(it => ({
        material: it.material,
        uom: it.uom,
        qty_per_batch: it.qty,
        unit_price: it.price,
        scrap_pct: it.scrap
      })),
      routing_ops: this.ops.map((op, idx) => ({
        op_seq: idx + 1,
        operation: op.operation,
        setup_time_hr: op.setup,
        run_time_hr: op.run,
        labor_rate: op.labor,
        machine_rate: op.machine
      })),
      overhead_pools: this.ohs.map(oh => ({
        name: oh.name,
        basis: oh.basis,
        rate: oh.rate
      }))
    };
    
    try {
      const resp = await fetch('/manufacturing/calculator', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      });
      
      const data = await resp.json();
      
      if (data.success) {
        this.currentRunId = data.run_id;
        alert('تم الحفظ بنجاح ✓');
        if (data.redirect) {
          window.location.href = data.redirect;
        }
      } else {
        alert('فشل الحفظ: ' + (data.message || 'خطأ غير معروف'));
      }
    } catch (e) {
      console.error('حفظ الخطأ:', e);
      alert('خطأ في الاتصال بالخادم');
    }
  },
  
  /**
   * روابط PDF/Excel
   */
  get pdfUrl() {
    if (this.currentRunId) {
      return `/manufacturing/runs/${this.currentRunId}/pdf`;
    }
    return '#';
  },
  
  get excelUrl() {
    if (this.currentRunId) {
      return `/manufacturing/runs/${this.currentRunId}/excel`;
    }
    return '#';
  }
});
