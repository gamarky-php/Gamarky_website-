<?php

return [
    // Shipping Modes
    'shipping' => [
        'sea' => 'Sea',
        'air' => 'Air',
        'land' => 'Land',
        'rail' => 'Rail',
    ],

    // Container Types
    'containers' => [
        '20ft_standard' => '20ft Standard',
        '40ft_standard' => '40ft Standard',
        '40ft_hc' => '40ft High Cube',
        '20ft_reefer' => '20ft Reefer',
        '40ft_reefer' => '40ft Reefer',
    ],

    // Shipment Types
    'shipment_types' => [
        'fcl' => 'Full Container Load (FCL)',
        'lcl' => 'Less than Container Load (LCL)',
        'bulk' => 'Bulk',
        'breakbulk' => 'Break Bulk',
    ],

    // Status
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'in_progress' => 'In Progress',
    ],

    // Languages
    'languages' => [
        'ar' => 'Arabic',
        'en' => 'English',
        'zh' => 'Chinese',
    ],
];
