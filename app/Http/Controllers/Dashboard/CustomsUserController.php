<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class CustomsUserController extends Controller
{
    public function index()
    {
        return view('dashboard.customs-user.index');
    }
}
