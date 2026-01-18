<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    /**
     * Display the agents management dashboard.
     */
    public function index()
    {
        return view('admin.agents.index');
    }
}
