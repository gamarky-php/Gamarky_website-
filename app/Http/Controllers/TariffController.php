<?php

namespace App\Http\Controllers;

class TariffController extends Controller
{
    public function index()
    {
        return view('tariffs.index');
    }
}
