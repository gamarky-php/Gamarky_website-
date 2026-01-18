<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierImportController extends Controller
{
    public function create()
    {
        // صفحة رفع الملف CSV/Excel
        return view('admin.suppliers.import-upload');
    }

    public function store(Request $request)
    {
        // TODO: معالجة ورفع الملف وقراءة أولية
        // خزّن البيانات مؤقتًا بالسيشن أو جدول مؤقت ثم وجّه للمراجعة
        return redirect()->route('admin.suppliers.import.review');
    }

    public function review()
    {
        // TODO: عرض البيانات قبل الاعتماد
        return view('admin.suppliers.import-review');
    }

    public function approve(Request $request)
    {
        // TODO: حفظ النهائي في جدول الموردين
        return redirect()->route('admin.suppliers.index')->with('status','تم اعتماد الموردين بنجاح.');
    }
}
