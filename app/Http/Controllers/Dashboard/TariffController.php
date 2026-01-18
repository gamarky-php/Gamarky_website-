<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class TariffController extends Controller
{
    public function index()
    {
        return view('dashboard.tariff.index');
    }
}
