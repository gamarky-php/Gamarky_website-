<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */

    // Generic Errors
    'generic' => 'حدث خلل',
    'generic_hint' => 'أعد المحاولة أو تواصل مع الدعم.',
    'something_went_wrong' => 'حدث خطأ ما',
    'try_again' => 'يرجى المحاولة مرة أخرى',
    
    // Network Errors
    'network_error' => 'خطأ في الاتصال',
    'network_error_hint' => 'تحقق من اتصالك بالإنترنت.',
    'connection_lost' => 'فقدت الاتصال بالإنترنت',
    'connection_restored' => 'تم استعادة الاتصال',
    
    // Server Errors
    'server_error' => 'خطأ في الخادم',
    'server_error_hint' => 'نعمل على حل المشكلة. حاول لاحقاً.',
    'service_unavailable' => 'الخدمة غير متاحة حالياً',
    'maintenance_mode' => 'الموقع قيد الصيانة',
    
    // Page Errors
    'not_found' => 'الصفحة غير موجودة',
    'not_found_hint' => 'تحقق من الرابط أو عُد للصفحة الرئيسية.',
    'page_not_found' => '404 - الصفحة غير موجودة',
    'go_home' => 'العودة للصفحة الرئيسية',
    
    // Authorization Errors
    'unauthorized' => 'غير مصرّح',
    'unauthorized_hint' => 'سجّل الدخول للمتابعة.',
    'forbidden' => 'غير مسموح',
    'forbidden_hint' => 'ليس لديك صلاحية للوصول.',
    'access_denied' => 'تم رفض الوصول',
    'permission_required' => 'صلاحية مطلوبة',
    
    // Validation Errors
    'validation_error' => 'خطأ في البيانات المُدخلة',
    'validation_error_hint' => 'تحقق من الحقول المُشار إليها.',
    'invalid_input' => 'مدخلات غير صحيحة',
    'required_fields' => 'يرجى ملء جميع الحقول المطلوبة',
    
    // Timeout Errors
    'timeout'=> 'انتهت مهلة الطلب',
    'timeout_hint' => 'استغرق الطلب وقتاً طويلاً. حاول مجدداً.',
    'request_timeout' => 'انتهت مهلة الطلب',
    
    // File Errors
    'file_too_large' => 'الملف كبير جداً',
    'file_too_large_hint' => 'الحد الأقصى {size} ميجا.',
    'invalid_format' => 'صيغة غير صحيحة',
    'invalid_format_hint' => 'استخدم صيغة {format}.',
    'upload_failed' => 'فشل رفع الملف',
    'file_not_found' => 'الملف غير موجود',
    
    // Data Errors
    'already_exists' => 'موجود مسبقاً',
    'not_found_in_db' => 'السجل غير موجود',
    'duplicate_entry' => 'إدخال مكرر',
    'data_corrupted' => 'البيانات تالفة',
    
    // Payment Errors
    'payment_failed' => 'فشلت عملية الدفع',
    'payment_failed_hint' => 'تحقق من بيانات بطاقتك.',
    'payment_declined' => 'تم رفض عملية الدفع',
    'insufficient_funds' => 'رصيد غير كافٍ',
    
    // Rate Limiting
    'too_many_requests' => 'طلبات كثيرة جداً',
    'rate_limit_exceeded' => 'تم تجاوز الحد المسموح',
    'please_wait' => 'يرجى الانتظار قبل المحاولة مرة أخرى',
];
