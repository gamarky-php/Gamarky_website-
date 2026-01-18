<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function index()
    {
        return view('dashboard.export.index');
    }
}
