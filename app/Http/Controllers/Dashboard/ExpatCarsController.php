<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ExpatCarsController extends Controller
{
    public function index()
    {
        return view('dashboard.expat_cars.index');
    }
}
