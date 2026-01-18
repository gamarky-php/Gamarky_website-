<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShippingTypeController extends Controller
{
    public function index()
    {
        $items = Cache::remember('shipping_types_list', 86400, function () {
            return ShippingType::orderBy('id')->get(['id','name','code']);
        });
        
        return ['results' => $items->map(fn($i)=>[
            'id'=>$i->id,'text'=>$i->name,'code'=>$i->code
        ])];
    }
    
    // Returns Select2 compatible format with optional mode filter
    // Example: /api/v1/shipping-types?mode=sea
    public function select2(Request $req)
    {
        $mode = $req->get('mode');
        
        $types = ShippingType::query()
            ->when($mode, fn($q) => $q->where('code', $mode))
            ->select('id', 'name as text', 'code')
            ->orderBy('name')
            ->get();
            
        return response()->json(['results' => $types]);
    }
}
