<?php

return [
    // Shipping Modes
    'shipping' => [
        'sea' => '海运',
        'air' => '空运',
        'land' => '陆运',
        'rail' => '铁路',
    ],

    // Container Types
    'containers' => [
        '20ft_standard' => '20英尺标准箱',
        '40ft_standard' => '40英尺标准箱',
        '40ft_hc' => '40英尺高柜',
        '20ft_reefer' => '20英尺冷藏箱',
        '40ft_reefer' => '40英尺冷藏箱',
    ],

    // Shipment Types
    'shipment_types' => [
        'fcl' => '整箱货 (FCL)',
        'lcl' => '拼箱货 (LCL)',
        'bulk' => '散货',
        'breakbulk' => '件杂货',
    ],

    // Status
    'status' => [
        'active' => '活跃',
        'inactive' => '未活跃',
        'pending' => '待处理',
        'approved' => '已批准',
        'rejected' => '已拒绝',
        'completed' => '已完成',
        'cancelled' => '已取消',
        'in_progress' => '进行中',
    ],

    // Languages
    'languages' => [
        'ar' => '阿拉伯语',
        'en' => '英语',
        'zh' => '中文',
    ],
];
