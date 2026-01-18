<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufacturingController extends Controller
{
    /**
     * Display the manufacturing management dashboard.
     */
    public function index()
    {
        return view('admin.manufacturing.index');
    }
}
