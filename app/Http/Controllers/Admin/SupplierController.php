<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->has('q')) {
            $query = $query->filter($request);
        }
        $suppliers = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:suppliers,slug',
            'country_code' => 'required|string|size:2',
            'city' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'categories' => 'nullable|array',
            'logo_path' => 'nullable|string|max:255',
            'approved' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if (!empty($data['categories']) && is_array($data['categories'])) {
            $data['categories'] = array_values($data['categories']);
        }
        $data['created_by'] = Auth::id();

        $supplier = Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'تم إنشاء المورد');
    }

    public function edit(\App\Models\Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved',
        ]);

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'تم تحديث المورد');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'تم حذف المورد');
    }

    public function approve(\App\Models\Supplier $supplier)
    {
        $supplier->status = 'approved'; // بدل approved=true
        $supplier->save();

        return back()->with('success', 'تم اعتماد المورد');
    }
}

