<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Display the export management dashboard.
     */
    public function index()
    {
        return view('admin.export.index');
    }
}
