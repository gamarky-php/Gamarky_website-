<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    public function home()
    {
        // عدّل اسم الفيو لو مختلف عندك
        return view('front.home');
    }
}
