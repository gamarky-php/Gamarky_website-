<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $r)
    {
        $r->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'mode'       => 'nullable|in:sea,air,land',
        ]);

        $q = Port::where('country_id', $r->country_id);
        if ($r->filled('mode')) $q->where('mode', $r->mode);

        $items = $q->orderBy('name')->get(['id','name']);
        return ['results' => $items->map(fn($i)=>['id'=>$i->id,'text'=>$i->name])];
    }
    
    // Filter ports by country and shipping mode
    // Example: /api/v1/ports?country_id=1&mode=sea
    public function byCountryAndMode(Request $req)
    {
        $countryId = (int)$req->get('country_id');
        $mode = $req->get('mode'); // sea | air | land
        
        $ports = Port::query()
            ->where('country_id', $countryId)
            ->when($mode, fn($q) => $q->where('mode', $mode))
            ->select('id', 'name as text')
            ->orderBy('name')
            ->get();
            
        return response()->json(['results' => $ports]);
    }
}
