<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping, Booking & Shipment Status
    |--------------------------------------------------------------------------
    */

    // Booking Messages
    'booking' => [
        'confirmed' => 'تم تأكيد حجزك.',
        'confirmed_with_ref' => 'تم تأكيد حجزك. رقم المرجع: :ref.',
        'pending' => 'حجزك قيد المراجعة.',
        'cancelled' => 'تم إلغاء الحجز.',
        'expired' => 'انتهت صلاحية الحجز.',
        'modified' => 'تم تعديل الحجز.',
        'payment_pending' => 'في انتظار الدفع.',
        'payment_received' => 'تم استلام الدفع.',
        'ready_for_pickup' => 'جاهز للاستلام.',
        'in_transit' => 'في الطريق.',
        'delivered' => 'تم التسليم.',
        'ref_label' => 'رقم المرجع',
        'booking_number' => 'رقم الحجز',
        'container_number' => 'رقم الحاوية',
        'tracking_number' => 'رقم التتبع',
        'success_truck' => 'تم حجز الشاحنة بنجاح!',
        'success_container' => 'تم حجز الشحنة بنجاح! سيتم التواصل معك خلال 24 ساعة.',
    ],

    // Shipment Status Messages
    'shipment' => [
        'status_draft' => 'مسودة',
        'status_pending' => 'قيد الانتظار',
        'status_confirmed' => 'مؤكدة',
        'status_in_transit' => 'في الطريق',
        'status_at_port' => 'في الميناء',
        'status_customs' => 'التخليص الجمركي',
        'status_delivered' => 'تم التسليم',
        'status_cancelled' => 'ملغاة',
        'estimated_arrival' => 'الوصول المتوقع: :date',
        'departed_from' => 'غادرت من :location',
        'arrived_at' => 'وصلت إلى :location',
        'delayed' => 'تأخير متوقع: :hours ساعة',
        'on_time' => 'في الموعد المحدد',
        'tracking_info' => 'معلومات التتبع',
        'shipment_updates' => 'تحديثات الشحنة',
    ],
    
    // Container & Cargo
    'container' => [
        'available' => 'متوفر',
        'unavailable' => 'غير متوفر',
        'loading' => 'جاري التحميل',
        'loaded' => 'تم التحميل',
        'in_transit' => 'في الطريق',
        'at_destination' => 'في الوجهة',
        'released' => 'تم الإفراج',
    ],
    
    // Ports & Routes
    'port' => [
        'origin' => 'ميناء المنشأ',
        'destination' => 'ميناء الوصول',
        'transit' => 'ميناء ترانزيت',
        'current_location' => 'الموقع الحالي',
    ],

    'actions' => [
        'track' => 'تتبع',
        'searching' => 'جاري البحث...',
        'search_quotes' => 'ابحث عن أسعار',
        'search_offers' => 'ابحث عن عروض',
        'book_now' => 'احجز الآن',
        'previous' => 'السابق',
        'next' => 'التالي',
        'confirm_booking' => 'تأكيد الحجز',
    ],

    'manufacturing' => [
        'placeholder_page' => 'التصنيع (صفحة مؤقتة)',
    ],

    'truck_tracker' => [
        'tracking_placeholder' => 'أدخل رقم التتبع (مثلاً: TRK123456)',
        'status' => 'الحالة',
        'current_location' => 'الموقع الحالي',
        'speed' => 'السرعة',
        'estimated_arrival' => 'الوصول المتوقع',
        'driver_info' => 'معلومات السائق',
        'driver_name' => 'الاسم:',
        'driver_phone' => 'الهاتف:',
        'truck_plate' => 'لوحة الشاحنة:',
        'live_map' => 'الخريطة المباشرة',
        'progress' => 'نسبة الإنجاز',
        'journey_log' => 'سجل الرحلة',
        'kmh' => 'كم/س',
        'kg' => 'كجم',
        'demo' => [
            'status_in_transit' => 'في الطريق',
            'current_location' => 'الرياض - طريق الدمام السريع',
            'driver_name' => 'أحمد محمد',
            'truck_plate' => 'ر ب ج 1234',
            'events' => [
                'start_location' => 'الرياض - نقطة انطلاق',
                'started' => 'بدء الرحلة',
                'station_location' => 'محطة وقود - الخرج',
                'short_stop' => 'توقف قصير',
                'on_road_location' => 'على الطريق',
                'in_transit' => 'في الطريق',
            ],
        ],
    ],

    'container_tracker' => [
        'tracking_placeholder' => 'أدخل رقم التتبع (مثلاً: MAEU123456789)',
        'current_status' => 'الحالة الحالية',
        'progress' => 'نسبة الإنجاز',
        'estimated_arrival' => 'الوصول المتوقع',
        'journey_log' => 'سجل الرحلة',
        'pending' => 'منتظر',
        'demo' => [
            'status_at_sea' => 'في البحر',
            'current_location' => 'قرب ميناء جدة',
            'events' => [
                'shanghai_port' => 'ميناء شنغهاي',
                'loaded' => 'تم التحميل',
                'at_sea' => 'في البحر',
                'in_transit' => 'في الطريق',
                'suez_crossing' => 'عبور قناة السويس',
                'crossing' => 'عبور قناة',
                'jeddah_port' => 'ميناء جدة',
                'expected_arrival' => 'متوقع الوصول',
            ],
        ],
    ],

    'truck_quote_form' => [
        'origin_city' => 'مدينة الانطلاق',
        'origin_city_placeholder' => 'مثلاً: الرياض',
        'destination_city' => 'مدينة الوصول',
        'destination_city_placeholder' => 'مثلاً: جدة',
        'pickup_date' => 'تاريخ الاستلام',
        'weight_kg' => 'الوزن (كجم)',
        'truck_type' => 'نوع الشاحنة',
        'price' => 'السعر:',
        'delivery_time' => 'مدة التوصيل:',
        'rating' => 'التقييم:',
        'points' => 'نقاط',
        'day' => 'يوم',
        'types' => [
            'flatbed' => 'شاحنة مفتوحة',
            'box' => 'شاحنة مغلقة',
            'refrigerated' => 'شاحنة مبردة',
            'tanker' => 'صهريج',
        ],
    ],

    'container_quote_form' => [
        'origin_port' => 'ميناء الشحن',
        'origin_port_placeholder' => 'مثلاً: Jeddah - KSA',
        'destination_port' => 'ميناء الوصول',
        'destination_port_placeholder' => 'مثلاً: Shanghai - CN',
        'loading_date' => 'تاريخ الشحن',
        'weight_kg' => 'الوزن (كجم)',
        'cbm' => 'الحجم (م³)',
        'cargo_type' => 'نوع الشحنة',
        'container_type' => 'نوع الحاوية',
        'price' => 'السعر:',
        'transit_duration' => 'مدة الشحن:',
        'valid_until' => 'صالح حتى:',
        'rating' => 'التقييم:',
        'points' => 'نقاط',
        'day' => 'يوم',
        'no_results' => 'عذراً، لم نجد عروض متاحة بهذه المعايير',
        'cargo_types' => [
            'general' => 'بضائع عامة',
            'hazmat' => 'مواد خطرة',
            'perishable' => 'قابلة للتلف',
            'fragile' => 'هشة',
        ],
        'container_types' => [
            '20GP' => '20 قدم عادي',
            '40GP' => '40 قدم عادي',
            '40HC' => '40 قدم عالي',
            '20RF' => '20 قدم مبرد',
            '40RF' => '40 قدم مبرد',
        ],
    ],

    'truck_booking_wizard' => [
        'stepper' => ['المسار', 'الحمولة', 'المستندات', 'الدفع', 'التأكيد'],
        'step_1_title' => 'الخطوة 1: تفاصيل المسار',
        'step_2_title' => 'الخطوة 2: بيانات الحمولة',
        'step_3_title' => 'الخطوة 3: المستندات',
        'step_4_title' => 'الخطوة 4: طريقة الدفع',
        'step_5_title' => 'الخطوة 5: التأكيد',
        'origin_city' => 'مدينة الانطلاق',
        'origin_city_placeholder' => 'الرياض',
        'destination_city' => 'مدينة الوصول',
        'destination_city_placeholder' => 'جدة',
        'pickup_date' => 'تاريخ الاستلام',
        'delivery_date' => 'تاريخ التسليم المتوقع',
        'weight_kg' => 'الوزن (كجم)',
        'truck_type' => 'نوع الشاحنة',
        'cargo_description' => 'وصف البضاعة',
        'cargo_description_placeholder' => 'صف البضاعة...',
        'invoice' => 'الفاتورة',
        'packing_list' => 'قائمة التعبئة',
        'uploaded' => 'تم الرفع',
        'terms_accept' => 'أوافق على الشروط والأحكام',
        'types' => [
            'flatbed' => 'شاحنة مفتوحة',
            'box' => 'شاحنة مغلقة',
            'refrigerated' => 'شاحنة مبردة',
        ],
        'payment' => [
            'bank_transfer' => 'تحويل بنكي',
            'credit_card' => 'بطاقة ائتمان',
            'cod' => 'دفع عند الاستلام',
        ],
        'summary' => [
            'route' => 'المسار:',
            'date' => 'التاريخ:',
            'weight' => 'الوزن:',
        ],
    ],

];
