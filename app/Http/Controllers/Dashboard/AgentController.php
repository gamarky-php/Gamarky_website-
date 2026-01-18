<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    public function index()
    {
        return view('dashboard.agent.index');
    }
}
