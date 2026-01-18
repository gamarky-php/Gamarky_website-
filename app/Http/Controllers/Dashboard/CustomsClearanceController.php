<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class CustomsClearanceController extends Controller
{
    public function index()
    {
        return view('dashboard.customs_clearance.index');
    }
}
