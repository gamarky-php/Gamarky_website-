<?php
return [
  'sections' => [
    [
      'key' => 'shipping',
      'title' => 'بورصة الحاويات والنقل',
      'route' => 'front.shipping.index',
      'children' => [
        ['title' => 'عرض سعر حاوية',     'route' => 'front.shipping.quote'],
        ['title' => 'احجز حاوية',         'route' => 'front.shipping.book'],
        ['title' => 'تتبع مسار حاوية',    'route' => 'front.shipping.track-container'],
        ['title' => 'عرض سيارة نقل',      'route' => 'front.shipping.truck-offers'],
        ['title' => 'احجز سيارة نقل',     'route' => 'front.shipping.book-truck'],
        ['title' => 'تتبع مسار البضاعة',  'route' => 'front.shipping.cargo-tracking'],
      ],
    ],
  ],
];
