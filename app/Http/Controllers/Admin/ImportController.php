<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display the import management dashboard.
     */
    public function index()
    {
        return view('admin.import.index');
    }
}
