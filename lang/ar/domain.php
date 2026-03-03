<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Domain/Business Static Options
    | (Not database content, but static select options)
    |--------------------------------------------------------------------------
    */

    // Shipping Modes
    'shipping' => [
        'sea' => 'بحري',
        'air' => 'جوي',
        'land' => 'بري',
        'rail' => 'سككي',
    ],

    // Container Types
    'containers' => [
        '20ft_standard' => '20 قدم (عادية)',
        '40ft_standard' => '40 قدم (عادية)',
        '40ft_hc' => '40 قدم (عالية)',
        '20ft_reefer' => '20 قدم (مبردة)',
        '40ft_reefer' => '40 قدم (مبردة)',
        '20ft_open_top' => '20 قدم (مفتوحة)',
        '40ft_open_top' => '40 قدم (مفتوحة)',
        'flat_rack' => 'فلات راك',
    ],

    // Shipment Types
    'shipment_types' => [
        'fcl' => 'حاوية كاملة (FCL)',
        'lcl' => 'شحنة مجمعة (LCL)',
        'bulk' => 'شحنة سائبة',
        'breakbulk' => 'شحنة عامة',
    ],

    // Incoterms
    'incoterms' => [
        'exw' => 'EXW - من المصنع',
        'fob' => 'FOB - على ظهر السفينة',
        'cif' => 'CIF - التكلفة والتأمين والشحن',
        'cfr' => 'CFR - التكلفة والشحن',
        'ddu' => 'DDU - التسليم غير مدفوع الرسوم',
        'ddp' => 'DDP - التسليم مدفوع الرسوم',
    ],

    // Status
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'قيد المراجعة',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'in_progress' => 'قيد التنفيذ',
    ],

    // Clearance Status
    'clearance_status' => [
        'not_started' => 'لم يبدأ',
        'in_customs' => 'في الجمارك',
        'under_review' => 'تحت المراجعة',
        'cleared' => 'تم التخليص',
        'held' => 'محجوز',
        'released' => 'تم الإفراج',
    ],

    // Payment Methods
    'payment_methods' => [
        'cash' => 'نقداً',
        'bank_transfer' => 'تحويل بنكي',
        'credit_card' => 'بطاقة ائتمان',
        'lc' => 'اعتماد مستندي',
        'cod' => 'الدفع عند الاستلام',
    ],

    // Business Sectors
    'sectors' => [
        'electronics' => 'إلكترونيات',
        'textiles' => 'منسوجات',
        'food' => 'مواد غذائية',
        'automotive' => 'سيارات',
        'machinery' => 'معدات',
        'chemicals' => 'كيماويات',
        'construction' => 'إنشاءات',
        'medical' => 'طبي',
        'furniture' => 'أثاث',
        'other' => 'أخرى',
    ],

    // Experience Years Options
    'experience' => [
        'less_1' => 'أقل من سنة',
        '1_3' => '1-3 سنوات',
        '3_5' => '3-5 سنوات',
        '5_10' => '5-10 سنوات',
        'more_10' => 'أكثر من 10 سنوات',
    ],

    // Units
    'units' => [
        'kg' => 'كيلوجرام',
        'ton' => 'طن',
        'cbm' => 'متر مكعب',
        'piece' => 'قطعة',
        'carton' => 'كرتون',
        'pallet' => 'باليت',
    ],

    // Languages
    'languages' => [
        'ar' => 'العربية',
        'en' => 'الإنجليزية',
        'zh' => 'الصينية',
    ],
];
