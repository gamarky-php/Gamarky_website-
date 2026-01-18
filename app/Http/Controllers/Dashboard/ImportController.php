<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index()
    {
        return view('dashboard.import.index');
    }
}
