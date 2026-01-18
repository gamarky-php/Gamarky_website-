<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ManufacturingController extends Controller
{
    public function index()
    {
        return view('dashboard.manufacturing.index');
    }
}
