<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PDF Document Labels and Static Text
    |--------------------------------------------------------------------------
    */

    // Document Titles
    'manufacturing_quote' => 'عرض أسعار تصنيع',
    'export_quote' => 'عرض أسعار تصدير',
    'import_quote' => 'عرض أسعار استيراد',
    'invoice' => 'فاتورة',
    'proforma_invoice' => 'فاتورة أولية',
    'packing_list' => 'قائمة التعبئة',
    'bill_of_lading' => 'بوليصة الشحن',

    // Document Sections
    'client_info' => 'معلومات العميل',
    'supplier_info' => 'معلومات المورد',
    'company_info' => 'معلومات الشركة',
    'shipment_details' => 'تفاصيل الشحنة',
    'product_details' => 'تفاصيل المنتج',
    'cost_breakdown' => 'تفاصيل التكلفة',
    'terms_conditions' => 'الشروط والأحكام',
    'notes' => 'ملاحظات',

    // Table Headers
    'item' => 'البند',
    'description' => 'الوصف',
    'quantity' => 'الكمية',
    'unit_price' => 'سعر الوحدة',
    'total' => 'المجموع',
    'subtotal' => 'المجموع الفرعي',
    'tax' => 'الضريبة',
    'grand_total' => 'المجموع الكلي',

    // Common Fields
    'date' => 'التاريخ',
    'quote_number' => 'رقم العرض',
    'invoice_number' => 'رقم الفاتورة',
    'reference_number' => 'الرقم المرجعي',
    'valid_until' => 'صالح حتى',
    'issued_by' => 'صادر عن',
    'page' => 'صفحة',
    'of' => 'من',

    // Manufacturing Specific
    'raw_materials' => 'المواد الخام',
    'labor_cost' => 'تكلفة العمالة',
    'production_cost' => 'تكلفة الإنتاج',
    'tooling_cost' => 'تكلفة القوالب',
    'packaging_cost' => 'تكلفة التعبئة',
    'manufacturing_time' => 'وقت التصنيع',
    'moq' => 'الحد الأدنى للطلب',

    // Shipping Specific
    'port_origin' => 'ميناء المنشأ',
    'port_destination' => 'ميناء الوصول',
    'shipping_mode' => 'وسيلة الشحن',
    'container_type' => 'نوع الحاوية',
    'freight_cost' => 'تكلفة الشحن',
    'insurance_cost' => 'تكلفة التأمين',
    'customs_duty' => 'الرسوم الجمركية',

    // Footer
    'thank_you' => 'شكراً لتعاملكم معنا',
    'contact_us' => 'للاستفسار يرجى التواصل',
    'email' => 'البريد الإلكتروني',
    'phone' => 'الهاتف',
    'website' => 'الموقع الإلكتروني',

    // Stamps & Signatures
    'signature' => 'التوقيع',
    'stamp' => 'الختم',
    'approved_by' => 'اعتمد بواسطة',
    'authorized_signature' => 'التوقيع المعتمد',

    // Status
    'draft' => 'مسودة',
    'final' => 'نهائي',
    'approved' => 'معتمد',
    'pending' => 'قيد المراجعة',

    // Export Quote Specific
    'export_quote_title' => 'عرض سعر تصدير',
    'quote_no' => 'رقم العرض',
    'client' => 'العميل',
    'incoterm' => 'الشرط التجاري',
    'loading_port' => 'ميناء التحميل',
    'discharge_port' => 'ميناء الوصول',
    'shipping_method' => 'طريقة الشحن',
    'currency' => 'العملة',
    'cost_details' => 'تفاصيل التكاليف',
    'item_description' => 'البيان',
    'category' => 'التصنيف',
    'amount' => 'المبلغ',
    'column_total' => 'إجمالي',
    'final_total' => 'الإجمالي النهائي',
    'total_cost' => 'التكلفة الإجمالية',
    'profit_margin' => 'هامش الربح',
    'final_sell_price' => 'سعر البيع النهائي',
    'price_per_ton' => 'سعر الطن',
    'notes' => 'ملاحظات',
    'validity_notice' => 'هذا العرض صالح لمدة 30 يومًا من تاريخ الإصدار',
    'created_by' => 'تم الإنشاء بواسطة',
    'system' => 'النظام',
    'not_specified' => 'غير محدد',

    // Cost Categories
    'category_manufacturing' => 'تصنيع',
    'category_packing' => 'تعبئة',
    'category_local_clearance' => 'تخليص محلي',
    'category_port_fees' => 'رسوم ميناء',
    'category_local_trucking' => 'نقل محلي',
    'category_freight' => 'شحن',
    'category_insurance' => 'تأمين',
    'category_bank' => 'بنوك',
    'category_docs' => 'مستندات',
    'category_extras' => 'إضافات',
    'category_profit' => 'ربح',

    // Manufacturing Quote Specific
    'mfg_quote_title' => 'عرض سعر تصنيع',
    'product' => 'المنتج',
    'batch_size' => 'حجم الدفعة',
    'valid_until' => 'صالح حتى',
    'bom_section' => 'قائمة المواد (BOM)',
    'material' => 'المادة',
    'qty_per_unit' => 'الكمية/وحدة',
    'total_materials' => 'إجمالي المواد',
    'operations_section' => 'عمليات التشغيل',
    'operation' => 'العملية',
    'setup_hours' => 'إعداد (ساعة)',
    'cycle_minutes' => 'دورة (دقيقة)',
    'total_operations' => 'إجمالي العمليات',
    'overheads_section' => 'تكاليف غير مباشرة',
    'overhead_item' => 'البند',
    'allocation_method' => 'طريقة التخصيص',
    'allocation_fixed' => 'ثابت',
    'allocation_percentage' => 'نسبة',
    'total_overheads' => 'إجمالي غير المباشرة',
    'cost_summary' => 'ملخص التكلفة والسعر',
    'batch_cost' => 'تكلفة الدفعة',
    'unit_cost' => 'تكلفة الوحدة',
    'unit_price' => 'سعر الوحدة',
    'qty' => 'الكمية',
    'final_total_amount' => 'الإجمالي النهائي',
    'quote_valid_until' => 'عرض السعر صالح حتى',
    'generated_by_system' => 'تم الإنشاء بواسطة نظام جماركي في',
];
