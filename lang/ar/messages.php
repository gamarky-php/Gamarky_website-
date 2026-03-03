<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flash Messages (Success, Error, Info, Warning)
    |--------------------------------------------------------------------------
    */

    // Success Messages
    'success' => 'تمت العملية بنجاح',
    'saved_successfully' => 'تم الحفظ بنجاح',
    'updated_successfully' => 'تم التحديث بنجاح',
    'deleted_successfully' => 'تم الحذف بنجاح',
    'created_successfully' => 'تم الإنشاء بنجاح',
    'cost_saved_successfully' => 'تم حفظ التكلفة بنجاح',

    // Error Messages
    'error' => 'حدث خطأ',
    'error_occurred' => 'حدث خطأ أثناء العملية',
    'not_found' => 'العنصر غير موجود',
    'unauthorized' => 'غير مصرح لك بهذه العملية',
    'validation_failed' => 'فشل التحقق من البيانات',

    // Google Auth Messages
    'google_login_cancelled' => 'تم إلغاء تسجيل الدخول عبر Google',
    'google_login_error' => 'خطأ في تسجيل الدخول عبر Google. يرجى المحاولة مرة أخرى',
    'google_login_error_general' => 'حدث خطأ أثناء تسجيل الدخول عبر Google',

    // Info Messages
    'info' => 'معلومة',
    'processing' => 'جاري المعالجة...',
    'loading' => 'جاري التحميل...',
    'please_wait' => 'يرجى الانتظار',

    // Warning Messages
    'warning' => 'تحذير',
    'confirm_delete' => 'هل أنت متأكد من الحذف؟',
    'cannot_undo' => 'لا يمكن التراجع عن هذه العملية',

    // Validation Messages (User-facing)
    'required_field' => 'هذا الحقل مطلوب',
    'invalid_email' => 'البريد الإلكتروني غير صالح',
    'invalid_phone' => 'رقم الهاتف غير صالح',
    'invalid_url' => 'رابط الموقع غير صالح',

    // Verification Messages (Use auth.php for profile verification states)
    'verification_code_sent' => 'تم إرسال رمز التحقق',

    // API/Service Messages
    'api_error' => 'خطأ في الاتصال بالخدمة',
    'service_unavailable' => 'الخدمة غير متاحة حالياً',
    'timeout_error' => 'انتهت مهلة الاتصال',

    // Upload Messages
    'upload_success' => 'تم رفع الملف بنجاح',
    'upload_failed' => 'فشل رفع الملف',
    'file_too_large' => 'حجم الملف كبير جداً',
    'invalid_file_type' => 'نوع الملف غير مدعوم',

    // Data Messages
    'no_data' => 'لا توجد بيانات',
    'no_results' => 'لا توجد نتائج',
    'data_loaded' => 'تم تحميل البيانات',

    // Booking Messages
    'booking_confirmed' => 'تم تأكيد الحجز',
    'booking_cancelled' => 'تم إلغاء الحجز',
    'booking_pending' => 'الحجز قيد المراجعة',
];
