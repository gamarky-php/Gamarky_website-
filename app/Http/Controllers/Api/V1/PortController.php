<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortController extends Controller
{
    /**
     * الحصول على الموانئ حسب البلد ونوع الشحن
     */
    public function byCountryAndMode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'mode' => 'nullable|string|in:sea,air,land',
            'q' => 'nullable|string|max:100',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $countryId = $request->country_id;
        $mode = $request->mode;
        $search = $request->get('q', '');
        $limit = $request->get('limit', 50);

        $query = Port::select('id', 'name', 'code', 'type', 'country_id', 'city', 'coordinates')
            ->with('country:id,name,code')
            ->where('country_id', $countryId)
            ->where('is_active', true);

        // فلترة حسب نوع الشحن
        if (!empty($mode)) {
            $query->where('type', $mode);
        }

        // البحث في الاسم أو الكود
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        $ports = $query->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $ports->map(function ($port) {
            return [
                'id' => $port->id,
                'text' => $port->name . ($port->city ? " ({$port->city})" : ''),
                'name' => $port->name,
                'code' => $port->code,
                'type' => $port->type,
                'city' => $port->city,
                'country' => [
                    'id' => $port->country->id,
                    'name' => $port->country->name,
                    'code' => $port->country->code,
                ],
                'coordinates' => $port->coordinates ? json_decode($port->coordinates, true) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'results' => $results,
                'count' => $ports->count(),
                'filters' => [
                    'country_id' => $countryId,
                    'mode' => $mode,
                    'search' => $search
                ]
            ]
        ]);
    }

    /**
     * الحصول على تفاصيل ميناء واحد
     */
    public function show(Request $request, $id)
    {
        $port = Port::with(['country', 'shippingRoutes'])
            ->where('is_active', true)
            ->find($id);

        if (!$port) {
            return response()->json([
                'success' => false,
                'message' => 'الميناء غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'port' => [
                    'id' => $port->id,
                    'name' => $port->name,
                    'code' => $port->code,
                    'type' => $port->type,
                    'city' => $port->city,
                    'address' => $port->address,
                    'coordinates' => $port->coordinates ? json_decode($port->coordinates, true) : null,
                    'country' => [
                        'id' => $port->country->id,
                        'name' => $port->country->name,
                        'code' => $port->country->code,
                    ],
                    'facilities' => $port->facilities ? json_decode($port->facilities, true) : [],
                    'contact_info' => $port->contact_info ? json_decode($port->contact_info, true) : null,
                    'routes_count' => $port->shippingRoutes->count(),
                ]
            ]
        ]);
    }

    /**
     * الحصول على الموانئ الأكثر استخداماً
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 10);
        $mode = $request->get('mode');

        $query = Port::select('ports.*')
            ->leftJoin('cost_calculations', 'ports.id', '=', 'cost_calculations.origin_port_id')
            ->selectRaw('ports.*, COUNT(cost_calculations.id) as usage_count')
            ->where('ports.is_active', true)
            ->with('country:id,name,code');

        if (!empty($mode)) {
            $query->where('ports.type', $mode);
        }

        $ports = $query->groupBy('ports.id')
            ->orderByDesc('usage_count')
            ->orderBy('ports.name')
            ->limit($limit)
            ->get();

        $results = $ports->map(function ($port) {
            return [
                'id' => $port->id,
                'name' => $port->name,
                'code' => $port->code,
                'type' => $port->type,
                'city' => $port->city,
                'country' => [
                    'id' => $port->country->id,
                    'name' => $port->country->name,
                    'code' => $port->country->code,
                ],
                'usage_count' => $port->usage_count ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'ports' => $results
            ]
        ]);
    }

    /**
     * الحصول على المسافات بين الموانئ
     */
    public function getDistance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $originPort = Port::find($request->origin_port_id);
        $destinationPort = Port::find($request->destination_port_id);

        // حساب المسافة (هنا يمكن استخدام API خارجي أو جدول مسافات محفوظ)
        $distance = $this->calculateDistance(
            json_decode($originPort->coordinates, true),
            json_decode($destinationPort->coordinates, true)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'origin' => [
                    'id' => $originPort->id,
                    'name' => $originPort->name,
                    'coordinates' => json_decode($originPort->coordinates, true)
                ],
                'destination' => [
                    'id' => $destinationPort->id,
                    'name' => $destinationPort->name,
                    'coordinates' => json_decode($destinationPort->coordinates, true)
                ],
                'distance' => [
                    'km' => $distance,
                    'nautical_miles' => round($distance * 0.539957, 2),
                    'estimated_time' => $this->estimateShippingTime($distance, $originPort->type)
                ]
            ]
        ]);
    }

    /**
     * حساب المسافة بين نقطتين (Haversine formula)
     */
    private function calculateDistance($coord1, $coord2)
    {
        if (!$coord1 || !$coord2) {
            return null;
        }

        $lat1 = deg2rad($coord1['lat']);
        $lon1 = deg2rad($coord1['lng']);
        $lat2 = deg2rad($coord2['lat']);
        $lon2 = deg2rad($coord2['lng']);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $earthRadius = 6371; // كم

        return round($earthRadius * $c, 2);
    }

    /**
     * تقدير وقت الشحن حسب المسافة ونوع النقل
     */
    private function estimateShippingTime($distance, $type)
    {
        if (!$distance) {
            return null;
        }

        $speed = [
            'sea' => 25, // عقدة في الساعة
            'air' => 800, // كم في الساعة
            'land' => 80 // كم في الساعة
        ];

        $typeSpeed = $speed[$type] ?? $speed['sea'];
        
        if ($type === 'sea') {
            $distance = $distance * 0.539957; // تحويل لعقدة بحرية
        }

        $hours = $distance / $typeSpeed;
        $days = ceil($hours / 24);

        return [
            'hours' => round($hours, 1),
            'days' => $days,
            'formatted' => $days . ' ' . ($days == 1 ? 'يوم' : 'أيام')
        ];
    }
}