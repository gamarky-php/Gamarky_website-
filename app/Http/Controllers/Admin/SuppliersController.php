<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $suppliers = Supplier::query()
            ->when($q, function ($query) use ($q) {
                $query->where('company_name', 'like', "%{$q}%")
                      ->orWhere('city', 'like', "%{$q}%")
                      ->orWhere('province', 'like', "%{$q}%")
                      ->orWhere('country_code', 'like', "%{$q}%")
                      ->orWhere('mobile_phone', 'like', "%{$q}%")
                      ->orWhere('tel', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.suppliers.index', compact('suppliers','q'));
    }
}
