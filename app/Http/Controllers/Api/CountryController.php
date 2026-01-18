<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function index()
    {
        $items = Cache::remember('countries_list', 86400, function () {
            return Country::orderBy('name')->get(['id','name']);
        });
        
        return ['results' => $items->map(fn($i)=>['id'=>$i->id,'text'=>$i->name])];
    }
    
    // Returns Select2 compatible format with search support
    public function select2(Request $req)
    {
        $q = trim($req->get('q', ''));
        $query = Country::query()->select('id', 'name as text');
        
        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }
        
        return response()->json([
            'results' => $query->orderBy('name')->limit(50)->get()
        ]);
    }
}
