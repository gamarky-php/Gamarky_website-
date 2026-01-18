<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query()->approved();
        $query = $query->filter($request);
        $suppliers = $query->orderBy('featured', 'desc')->paginate(12);

        return view('front.suppliers.index', compact('suppliers'));
    }
}

