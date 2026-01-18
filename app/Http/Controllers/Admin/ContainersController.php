<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContainersController extends Controller
{
    /**
     * Display the containers exchange management dashboard.
     */
    public function index()
    {
        return view('admin.containers.index');
    }
}
