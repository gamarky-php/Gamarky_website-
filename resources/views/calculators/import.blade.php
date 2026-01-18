<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>حاسبة التكلفة - الاستيراد</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" x-data="importCalculator()">
    <div class="min-h-screen p-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">حاسبة تكلفة الاستيراد</h1>
                    <p class="text-sm text-gray-500 mt-1">احسب تكاليف الاستيراد بدقة مع هوامش الربح</p>
                </div>
                <div class="flex gap-2">
                    <button @click="saveScenario" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        حفظ كسيناريو
                    </button>
                    <button @click="exportPDF" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        تصدير PDF
                    </button>
                    <button @click="exportExcel" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        تصدير Excel
                    </button>
                </div>
            </div>
            <div x-show="refCode" class="mt-2">
                <span class="text-sm font-semibold text-blue-600">رقم المرجع: <span x-text="refCode"></span></span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <!-- Right Panel: Filters & Inputs (15%) -->
            <div class="col-span-12 lg:col-span-2 space-y-4">
                <!-- Presets -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h3 class="font-semibold mb-3 text-gray-700">سيناريوهات جاهزة</h3>
                    <div class="space-y-2">
                        <button @click="loadPreset('china-standard')" class="w-full text-sm px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">
                            الصين - قياسي
                        </button>
                        <button @click="loadPreset('china-express')" class="w-full text-sm px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">
                            الصين - سريع
                        </button>
                        <button @click="loadPreset('eu-lcl')" class="w-full text-sm px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">
                            أوروبا - LCL
                        </button>
                    </div>
                </div>

                <!-- Basic Inputs -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <h3 class="font-semibold mb-3 text-gray-700">معلومات أساسية</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">العملة</label>
                            <select x-model="currency" class="w-full text-sm border rounded px-2 py-1.5">
                                <option value="USD">USD</option>
                                <option value="SAR">SAR</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">سعر الشراء</label>
                            <input type="number" x-model="inputs.purchase_price" @input="calculate" 
                                   class="w-full text-sm border rounded px-2 py-1.5">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Incoterm</label>
                            <select x-model="inputs.incoterm" class="w-full text-sm border rounded px-2 py-1.5">
                                <option value="FOB">FOB</option>
                                <option value="CFR">CFR</option>
                                <option value="CIF">CIF</option>
                                <option value="EXW">EXW</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">بلد المنشأ</label>
                            <select x-model="inputs.origin_country" class="w-full text-sm border rounded px-2 py-1.5">
                                <option value="CN">الصين</option>
                                <option value="US">أمريكا</option>
                                <option value="DE">ألمانيا</option>
                                <option value="IT">إيطاليا</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">ميناء الوصول</label>
                            <select x-model="inputs.destination_port" class="w-full text-sm border rounded px-2 py-1.5">
                                <option value="EG-ALY">الإسكندرية</option>
                                <option value="SA-JED">جدة</option>
                                <option value="AE-DXB">دبي</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">نوع الحاوية</label>
                            <select x-model="inputs.container_type" class="w-full text-sm border rounded px-2 py-1.5">
                                <option value="20GP">20GP</option>
                                <option value="40GP">40GP</option>
                                <option value="40HC">40HC</option>
                                <option value="LCL">LCL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Panel: Items Table & Totals (85%) -->
            <div class="col-span-12 lg:col-span-10 space-y-4">
                <!-- Items Table -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-700">بنود التكلفة</h3>
                        <button @click="addItem" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            + إضافة بند
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">البند</th>
                                    <th class="px-4 py-2 text-right">القيمة</th>
                                    <th class="px-4 py-2 text-right">النسبة %</th>
                                    <th class="px-4 py-2 text-center">إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-t">
                                        <td class="px-4 py-2">
                                            <select x-model="item.key" class="w-full border rounded px-2 py-1">
                                                <option value="international_freight">شحن دولي</option>
                                                <option value="insurance">تأمين</option>
                                                <option value="local_port_fees">رسوم ميناء محلية</option>
                                                <option value="customs_duties">رسوم جمركية</option>
                                                <option value="clearance_fees">رسوم تخليص</option>
                                                <option value="inland_transport">نقل داخلي</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="number" x-model="item.value" @input="calculate" 
                                                   class="w-full border rounded px-2 py-1">
                                        </td>
                                        <td class="px-4 py-2 text-gray-600" x-text="calculateItemPercent(item.value)"></td>
                                        <td class="px-4 py-2 text-center">
                                            <button @click="removeItem(index)" class="text-red-600 hover:text-red-800">
                                                حذف
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals & KPIs -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Totals -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="font-semibold mb-4 text-gray-700">الإجماليات</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">COGS:</span>
                                <span class="font-semibold" x-text="formatCurrency(totals.cogs)"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">هامش الربح %</label>
                                <input type="number" x-model="marginPercent" @input="calculate" 
                                       class="w-full border rounded px-2 py-1.5 text-sm">
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">قيمة الهامش:</span>
                                <span class="font-semibold text-green-600" x-text="formatCurrency(totals.margin_value)"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="text-gray-900 font-semibold">سعر البيع:</span>
                                <span class="font-bold text-lg text-blue-600" x-text="formatCurrency(totals.sell_price)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- KPIs -->
                    <div class="bg-white rounded-lg shadow-sm p-4 lg:col-span-2">
                        <h3 class="font-semibold mb-4 text-gray-700">المؤشرات الرئيسية</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded">
                                <div class="text-2xl font-bold text-blue-600" x-text="kpis.duty_ratio + '%'"></div>
                                <div class="text-xs text-gray-600 mt-1">نسبة الرسوم الجمركية</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded">
                                <div class="text-2xl font-bold text-green-600" x-text="kpis.logistics_share + '%'"></div>
                                <div class="text-xs text-gray-600 mt-1">حصة اللوجستيات</div>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded">
                                <div class="text-2xl font-bold text-orange-600" x-text="kpis.lead_time_days + ' يوم'"></div>
                                <div class="text-xs text-gray-600 mt-1">وقت التسليم المتوقع</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function importCalculator() {
            return {
                currency: 'USD',
                inputs: {
                    purchase_price: 12000,
                    incoterm: 'FOB',
                    origin_country: 'CN',
                    destination_port: 'EG-ALY',
                    container_type: '40HC',
                    gross_weight_kg: 9800,
                    cbm: 62
                },
                items: [
                    { key: 'international_freight', value: 2400 },
                    { key: 'insurance', value: 85 },
                    { key: 'local_port_fees', value: 560 },
                    { key: 'customs_duties', value: 1800 },
                    { key: 'clearance_fees', value: 450 },
                    { key: 'inland_transport', value: 320 }
                ],
                marginPercent: 18,
                totals: {
                    cogs: 0,
                    margin_value: 0,
                    sell_price: 0
                },
                kpis: {
                    duty_ratio: 0,
                    logistics_share: 0,
                    lead_time_days: 0
                },
                refCode: '',
                calculationId: null,

                init() {
                    this.calculate();
                },

                calculate() {
                    const data = {
                        module: 'import',
                        currency: this.currency,
                        inputs: this.inputs,
                        items: this.items,
                        margin_percent: this.marginPercent
                    };

                    fetch('/api/costs/calculate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            this.totals = result.totals;
                            this.kpis = result.kpis;
                            this.refCode = result.ref_code;
                            this.calculationId = result.calculation_id;
                        }
                    })
                    .catch(err => console.error('Calculation error:', err));
                },

                addItem() {
                    this.items.push({ key: 'international_freight', value: 0 });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculate();
                },

                calculateItemPercent(value) {
                    if (this.totals.cogs === 0) return '0%';
                    return ((value / this.totals.cogs) * 100).toFixed(1) + '%';
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('ar-EG', {
                        style: 'currency',
                        currency: this.currency
                    }).format(value);
                },

                loadPreset(preset) {
                    const presets = {
                        'china-standard': {
                            inputs: {
                                purchase_price: 12000,
                                incoterm: 'FOB',
                                origin_country: 'CN',
                                destination_port: 'EG-ALY',
                                container_type: '40HC'
                            },
                            items: [
                                { key: 'international_freight', value: 2400 },
                                { key: 'insurance', value: 85 },
                                { key: 'local_port_fees', value: 560 },
                                { key: 'customs_duties', value: 1800 },
                                { key: 'clearance_fees', value: 450 },
                                { key: 'inland_transport', value: 320 }
                            ]
                        },
                        'china-express': {
                            inputs: {
                                purchase_price: 8000,
                                incoterm: 'FOB',
                                origin_country: 'CN',
                                destination_port: 'AE-DXB',
                                container_type: '20GP'
                            },
                            items: [
                                { key: 'international_freight', value: 1800 },
                                { key: 'insurance', value: 60 },
                                { key: 'local_port_fees', value: 420 },
                                { key: 'customs_duties', value: 0 },
                                { key: 'clearance_fees', value: 350 },
                                { key: 'inland_transport', value: 250 }
                            ]
                        }
                    };

                    if (presets[preset]) {
                        this.inputs = { ...this.inputs, ...presets[preset].inputs };
                        this.items = presets[preset].items;
                        this.calculate();
                    }
                },

                saveScenario() {
                    alert('تم حفظ السيناريو برقم: ' + this.refCode);
                },

                exportPDF() {
                    if (this.calculationId) {
                        window.open(`/api/costs/${this.calculationId}/pdf`, '_blank');
                    }
                },

                exportExcel() {
                    if (this.calculationId) {
                        window.open(`/api/costs/${this.calculationId}/excel`, '_blank');
                    }
                }
            }
        }
    </script>
</body>
</html>
