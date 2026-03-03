<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shipping, Booking & Shipment Status
    |--------------------------------------------------------------------------
    */

    // Booking Messages
    'booking' => [
        'confirmed' => '您的预订已确认。',
        'confirmed_with_ref' => '您的预订已确认。参考号：:ref。',
        'pending' => '您的预订正在审核中。',
        'cancelled' => '预订已取消。',
        'expired' => '预订已过期。',
        'modified' => '预订已修改。',
        'payment_pending' => '等待付款。',
        'payment_received' => '已收到付款。',
        'ready_for_pickup' => '准备取货。',
        'in_transit' => '运输中。',
        'delivered' => '已交付。',
        'ref_label' => '参考号',
        'booking_number' => '预订号',
        'container_number' => '集装箱号',
        'tracking_number' => '跟踪号',
        'success_truck' => '卡车预订提交成功！',
        'success_container' => '货运预订提交成功！我们将在24小时内联系您。',
    ],

    // Shipment Status Messages
    'shipment' => [
        'status_draft' => '草稿',
        'status_pending' => '待处理',
        'status_confirmed' => '已确认',
        'status_in_transit' => '运输中',
        'status_at_port' => '在港口',
        'status_customs' => '清关中',
        'status_delivered' => '已交付',
        'status_cancelled' => '已取消',
        'estimated_arrival' => '预计到达：:date',
        'departed_from' => '从 :location 发出',
        'arrived_at' => '到达 :location',
        'delayed' => '延误 :hours 小时',
        'on_time' => '准时',
        'tracking_info' => '跟踪信息',
        'shipment_updates' => '货运更新',
    ],
    
    // Container & Cargo
    'container' => [
        'available' => '可用',
        'unavailable' => '不可用',
        'loading' => '装载中',
        'loaded' => '已装载',
        'in_transit' => '运输中',
        'at_destination' => '在目的地',
        'released' => '已放行',
    ],
    
    // Ports & Routes
    'port' => [
        'origin' => '起始港口',
        'destination' => '目的港口',
        'transit' => '中转港口',
        'current_location' => '当前位置',
    ],

    'actions' => [
        'track' => '跟踪',
        'searching' => '搜索中...',
        'search_quotes' => '搜索报价',
        'search_offers' => '搜索方案',
        'book_now' => '立即预订',
        'previous' => '上一步',
        'next' => '下一步',
        'confirm_booking' => '确认预订',
    ],

    'manufacturing' => [
        'placeholder_page' => '制造（临时页面）',
    ],

    'truck_tracker' => [
        'tracking_placeholder' => '输入跟踪号（例如：TRK123456）',
        'status' => '状态',
        'current_location' => '当前位置',
        'speed' => '速度',
        'estimated_arrival' => '预计到达',
        'driver_info' => '司机信息',
        'driver_name' => '姓名：',
        'driver_phone' => '电话：',
        'truck_plate' => '车牌：',
        'live_map' => '实时地图',
        'progress' => '进度',
        'journey_log' => '行程记录',
        'kmh' => '公里/小时',
        'kg' => '公斤',
        'demo' => [
            'status_in_transit' => '运输中',
            'current_location' => '利雅得 - 达曼高速',
            'driver_name' => '艾哈迈德·穆罕默德',
            'truck_plate' => 'R B J 1234',
            'events' => [
                'start_location' => '利雅得 - 出发点',
                'started' => '开始行程',
                'station_location' => '加油站 - 海尔季',
                'short_stop' => '短暂停靠',
                'on_road_location' => '在途中',
                'in_transit' => '运输中',
            ],
        ],
    ],

    'container_tracker' => [
        'tracking_placeholder' => '输入跟踪号（例如：MAEU123456789）',
        'current_status' => '当前状态',
        'progress' => '进度',
        'estimated_arrival' => '预计到达',
        'journey_log' => '行程记录',
        'pending' => '等待中',
        'demo' => [
            'status_at_sea' => '海上运输',
            'current_location' => '靠近吉达港',
            'events' => [
                'shanghai_port' => '上海港',
                'loaded' => '已装载',
                'at_sea' => '海上运输',
                'in_transit' => '运输中',
                'suez_crossing' => '苏伊士运河过境',
                'crossing' => '运河过境',
                'jeddah_port' => '吉达港',
                'expected_arrival' => '预计到达',
            ],
        ],
    ],

    'truck_quote_form' => [
        'origin_city' => '出发城市',
        'origin_city_placeholder' => '例如：利雅得',
        'destination_city' => '到达城市',
        'destination_city_placeholder' => '例如：吉达',
        'pickup_date' => '提货日期',
        'weight_kg' => '重量（公斤）',
        'truck_type' => '卡车类型',
        'price' => '价格：',
        'delivery_time' => '运输时长：',
        'rating' => '评分：',
        'points' => '分',
        'day' => '天',
        'types' => [
            'flatbed' => '平板卡车',
            'box' => '厢式卡车',
            'refrigerated' => '冷藏卡车',
            'tanker' => '罐车',
        ],
    ],

    'container_quote_form' => [
        'origin_port' => '起运港',
        'origin_port_placeholder' => '例如：Jeddah - KSA',
        'destination_port' => '目的港',
        'destination_port_placeholder' => '例如：Shanghai - CN',
        'loading_date' => '装运日期',
        'weight_kg' => '重量（公斤）',
        'cbm' => '体积（m³）',
        'cargo_type' => '货物类型',
        'container_type' => '集装箱类型',
        'price' => '价格：',
        'transit_duration' => '运输时长：',
        'valid_until' => '有效期至：',
        'rating' => '评分：',
        'points' => '分',
        'day' => '天',
        'no_results' => '抱歉，未找到符合条件的报价。',
        'cargo_types' => [
            'general' => '普通货物',
            'hazmat' => '危险品',
            'perishable' => '易腐货物',
            'fragile' => '易碎货物',
        ],
        'container_types' => [
            '20GP' => '20英尺标准箱',
            '40GP' => '40英尺标准箱',
            '40HC' => '40英尺高箱',
            '20RF' => '20英尺冷藏箱',
            '40RF' => '40英尺冷藏箱',
        ],
    ],

    'truck_booking_wizard' => [
        'stepper' => ['路线', '货物', '文件', '支付', '确认'],
        'step_1_title' => '第1步：路线详情',
        'step_2_title' => '第2步：货物信息',
        'step_3_title' => '第3步：文件',
        'step_4_title' => '第4步：支付方式',
        'step_5_title' => '第5步：确认',
        'origin_city' => '出发城市',
        'origin_city_placeholder' => '利雅得',
        'destination_city' => '到达城市',
        'destination_city_placeholder' => '吉达',
        'pickup_date' => '提货日期',
        'delivery_date' => '预计送达日期',
        'weight_kg' => '重量（公斤）',
        'truck_type' => '卡车类型',
        'cargo_description' => '货物描述',
        'cargo_description_placeholder' => '请描述货物...',
        'invoice' => '发票',
        'packing_list' => '装箱单',
        'uploaded' => '已上传',
        'terms_accept' => '我同意条款和条件',
        'types' => [
            'flatbed' => '平板卡车',
            'box' => '厢式卡车',
            'refrigerated' => '冷藏卡车',
        ],
        'payment' => [
            'bank_transfer' => '银行转账',
            'credit_card' => '信用卡',
            'cod' => '货到付款',
        ],
        'summary' => [
            'route' => '路线：',
            'date' => '日期：',
            'weight' => '重量：',
        ],
    ],
];
