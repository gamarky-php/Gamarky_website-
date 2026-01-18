<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * الحصول على قائمة البلدان للاختيار (Select2 format)
     */
    public function select2(Request $request)
    {
        $search = $request->get('q', '');
        $limit = $request->get('limit', 50);

        $query = Country::select('id', 'name', 'code', 'flag_url')
            ->where('is_active', true);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $countries = $query->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'text' => $country->name,
                'code' => $country->code,
                'flag' => $country->flag_url,
                'name_en' => $country->name_en ?? $country->name,
                'name_ar' => $country->name_ar ?? $country->name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'results' => $results,
                'pagination' => [
                    'more' => $countries->count() >= $limit
                ]
            ]
        ]);
    }

    /**
     * الحصول على تفاصيل بلد واحد
     */
    public function show(Request $request, $id)
    {
        $country = Country::with(['ports', 'users'])
            ->where('is_active', true)
            ->find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'البلد غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'country' => [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'flag' => $country->flag_url,
                    'currency' => $country->currency_code,
                    'timezone' => $country->timezone,
                    'calling_code' => $country->calling_code,
                    'ports_count' => $country->ports->count(),
                    'active_users' => $country->users()->where('is_active', true)->count(),
                ]
            ]
        ]);
    }

    /**
     * الحصول على البلدان الأكثر استخداماً
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 10);

        $countries = Country::select('countries.*')
            ->leftJoin('users', 'countries.id', '=', 'users.country_id')
            ->selectRaw('countries.*, COUNT(users.id) as users_count')
            ->where('countries.is_active', true)
            ->groupBy('countries.id')
            ->orderByDesc('users_count')
            ->orderBy('countries.name')
            ->limit($limit)
            ->get();

        $results = $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
                'flag' => $country->flag_url,
                'users_count' => $country->users_count ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'countries' => $results
            ]
        ]);
    }

    /**
     * البحث في البلدان
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $limit = $request->get('limit', 20);

        if (empty($search)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال نص للبحث'
            ], 400);
        }

        $countries = Country::select('id', 'name', 'code', 'flag_url')
            ->where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('name_ar', 'LIKE', "%{$search}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
                'flag' => $country->flag_url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'results' => $results,
                'count' => $countries->count(),
                'search_term' => $search
            ]
        ]);
    }
}