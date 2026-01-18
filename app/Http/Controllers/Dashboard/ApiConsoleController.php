<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ApiConsoleController extends Controller
{
    public function index()
    {
        return view('dashboard.api.index');
    }
}
