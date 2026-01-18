<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ContainersController extends Controller
{
    public function index()
    {
        return view('dashboard.containers.index');
    }
}
