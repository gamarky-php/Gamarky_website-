<?php

namespace App\Http\Controllers;

class ImportController extends Controller
{
    public function calculator()
    {
        // مؤقتًا نعرض فيو بسيط لحين ربط الصفحة الحقيقية
        return view('import.calculator');
    }

    public function procedures()
    {
        return view('import.procedures');
    }

    public function tracking()
    {
        return view('import.tracking');
    }
}
