<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CostCalculationResource;
use App\Models\CostCalculation;
use App\Models\Port;
use App\Models\ShippingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CostCalculatorController extends Controller
{
    /**
     * حساب تكلفة الشحن
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id|different:origin_port_id',
            'shipping_type_id' => 'required|exists:shipping_types,id',
            'weight' => 'required|numeric|min:0.1|max:50000',
            'volume' => 'nullable|numeric|min:0.01|max:1000',
            'dimensions' => 'nullable|array',
            'dimensions.length' => 'nullable|numeric|min:1',
            'dimensions.width' => 'nullable|numeric|min:1', 
            'dimensions.height' => 'nullable|numeric|min:1',
            'cargo_type' => 'nullable|string|max:100',
            'cargo_description' => 'nullable|string|max:500',
            'declared_value' => 'nullable|numeric|min:1',
            'currency' => 'nullable|string|size:3|in:USD,EUR,SAR,AED',
            'insurance_required' => 'boolean',
            'save_calculation' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // الحصول على البيانات المطلوبة
            $originPort = Port::with('country')->find($request->origin_port_id);
            $destinationPort = Port::with('country')->find($request->destination_port_id);
            $shippingType = ShippingType::find($request->shipping_type_id);

            // التحقق من توافق نوع الشحن مع الموانئ
            if ($originPort->type !== $shippingType->mode || $destinationPort->type !== $shippingType->mode) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الشحن غير متوافق مع الموانئ المختارة'
                ], 400);
            }

            // حساب المسافة
            $distance = $this->calculateDistance($originPort, $destinationPort);

            // حساب التكاليف
            $calculation = $this->performCalculation([
                'origin_port' => $originPort,
                'destination_port' => $destinationPort,
                'shipping_type' => $shippingType,
                'weight' => $request->weight,
                'volume' => $request->volume ?? $this->calculateVolumeFromDimensions($request->dimensions),
                'dimensions' => $request->dimensions,
                'cargo_type' => $request->cargo_type,
                'cargo_description' => $request->cargo_description,
                'declared_value' => $request->declared_value ?? ($request->weight * 100), // تقدير افتراضي
                'currency' => $request->currency ?? 'USD',
                'insurance_required' => $request->insurance_required ?? false,
                'distance' => $distance,
            ]);

            // حفظ الحساب إذا طُلب ذلك
            if ($request->save_calculation && $request->user()) {
                $savedCalculation = $this->saveCalculation($calculation, $request->user());
                $calculation['id'] = $savedCalculation->id;
                $calculation['reference_number'] = $savedCalculation->reference_number;
                $calculation['saved_at'] = $savedCalculation->created_at->toISOString();
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حساب التكلفة بنجاح',
                'data' => [
                    'calculation' => $calculation,
                    'recommendations' => $this->getRecommendations($calculation),
                    'alternatives' => $this->getAlternatives($request, $calculation),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حساب التكلفة',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * الحصول على الحسابات المحفوظة للمستخدم
     */
    public function getSaved(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');
        $search = $request->get('search');

        $query = $request->user()
            ->costCalculations()
            ->with(['originPort', 'destinationPort', 'shippingType'])
            ->latest();

        // فلترة حسب الحالة
        if ($status) {
            $query->where('status', $status);
        }

        // البحث
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'LIKE', "%{$search}%")
                  ->orWhere('cargo_description', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }

        $calculations = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'calculations' => CostCalculationResource::collection($calculations->items()),
                'pagination' => [
                    'current_page' => $calculations->currentPage(),
                    'last_page' => $calculations->lastPage(),
                    'per_page' => $calculations->perPage(),
                    'total' => $calculations->total(),
                    'has_more' => $calculations->hasMorePages(),
                ]
            ]
        ]);
    }

    /**
     * الحصول على حساب محدد
     */
    public function getById(Request $request, $id)
    {
        $calculation = CostCalculation::with(['originPort', 'destinationPort', 'shippingType', 'user'])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$calculation) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'calculation' => new CostCalculationResource($calculation)
            ]
        ]);
    }

    /**
     * حذف حساب
     */
    public function delete(Request $request, $id)
    {
        $calculation = CostCalculation::where('user_id', $request->user()->id)->find($id);

        if (!$calculation) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود'
            ], 404);
        }

        $calculation->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحساب بنجاح'
        ]);
    }

    /**
     * تصدير PDF
     */
    public function exportPdf(Request $request, $id)
    {
        $calculation = CostCalculation::with(['originPort', 'destinationPort', 'shippingType', 'user'])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$calculation) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود'
            ], 404);
        }

        try {
            // هنا يمكن استخدام مكتبة PDF مثل DomPDF أو Snappy
            $pdfPath = $this->generatePdf($calculation);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء ملف PDF بنجاح',
                'data' => [
                    'download_url' => url($pdfPath),
                    'file_name' => 'cost-calculation-' . $calculation->reference_number . '.pdf'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء ملف PDF'
            ], 500);
        }
    }

    /**
     * تصدير Excel
     */
    public function exportExcel(Request $request, $id)
    {
        $calculation = CostCalculation::with(['originPort', 'destinationPort', 'shippingType', 'user'])
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$calculation) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود'
            ], 404);
        }

        try {
            // هنا يمكن استخدام Laravel Excel
            $excelPath = $this->generateExcel($calculation);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء ملف Excel بنجاح',
                'data' => [
                    'download_url' => url($excelPath),
                    'file_name' => 'cost-calculation-' . $calculation->reference_number . '.xlsx'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء ملف Excel'
            ], 500);
        }
    }

    /**
     * حساب المسافة بين ميناءين
     */
    private function calculateDistance($originPort, $destinationPort)
    {
        $originCoords = json_decode($originPort->coordinates, true);
        $destCoords = json_decode($destinationPort->coordinates, true);

        if (!$originCoords || !$destCoords) {
            return 1000; // مسافة افتراضية
        }

        // Haversine formula
        $lat1 = deg2rad($originCoords['lat']);
        $lon1 = deg2rad($originCoords['lng']);
        $lat2 = deg2rad($destCoords['lat']);
        $lon2 = deg2rad($destCoords['lng']);

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
     * حساب الحجم من الأبعاد
     */
    private function calculateVolumeFromDimensions($dimensions)
    {
        if (!$dimensions || !isset($dimensions['length'], $dimensions['width'], $dimensions['height'])) {
            return null;
        }

        return ($dimensions['length'] * $dimensions['width'] * $dimensions['height']) / 1000000; // تحويل لمتر مكعب
    }

    /**
     * تنفيذ حساب التكلفة
     */
    private function performCalculation($data)
    {
        $weight = $data['weight'];
        $volume = $data['volume'] ?? 0;
        $distance = $data['distance'];
        $shippingType = $data['shipping_type'];
        $declaredValue = $data['declared_value'];

        // التكلفة الأساسية
        $baseRate = $this->getBaseRate($shippingType->mode, $weight, $volume);
        $baseFfreight = $baseRate * max($weight, $volume * 167); // Weight or volumetric weight

        // المضاعف حسب نوع الشحن
        $multiplier = $shippingType->price_multiplier ?? 1.0;
        $baseFlight = $baseFright * $multiplier;

        // حساب الرسوم الإضافية
        $fuelSurcharge = $baseFlight * 0.15; // 15% رسوم وقود
        $securityFee = $weight * 0.5; // 0.5$ لكل كغ
        $documentationFee = 25; // رسم ثابت
        $handlingFee = max(50, $weight * 2); // 2$ لكل كغ مع حد أدنى 50$
        
        // التأمين
        $insuranceFee = $data['insurance_required'] ? ($declaredValue * 0.002) : 0; // 0.2%
        
        // رسوم جمركية تقديرية
        $customsFee = $declaredValue * 0.05; // 5%
        
        // رسوم الموانئ
        $portCharges = 100; // رسم ثابت

        // حساب المجاميع
        $subtotal = $baseFlight + $fuelSurcharge + $securityFee + $documentationFee + 
                   $handlingFee + $insuranceFee + $customsFee + $portCharges;
        
        $taxRate = 0.15; // 15% ضريبة
        $taxAmount = $subtotal * $taxRate;
        $totalCost = $subtotal + $taxAmount;

        // تقدير وقت التسليم
        $estimatedTransitTime = $this->calculateTransitTime($distance, $shippingType->mode);

        return [
            'origin_port_id' => $data['origin_port']->id,
            'destination_port_id' => $data['destination_port']->id,
            'shipping_type_id' => $shippingType->id,
            'weight' => $weight,
            'volume' => $volume,
            'dimensions' => $data['dimensions'],
            'cargo_type' => $data['cargo_type'],
            'cargo_description' => $data['cargo_description'],
            'declared_value' => $declaredValue,
            'currency' => $data['currency'],
            'estimated_distance' => $distance,
            'estimated_transit_time' => $estimatedTransitTime,
            'base_freight' => round($baseFlight, 2),
            'fuel_surcharge' => round($fuelSurcharge, 2),
            'security_fee' => round($securityFee, 2),
            'documentation_fee' => $documentationFee,
            'handling_fee' => round($handlingFee, 2),
            'insurance_fee' => round($insuranceFee, 2),
            'customs_fee' => round($customsFee, 2),
            'port_charges' => $portCharges,
            'subtotal' => round($subtotal, 2),
            'tax_rate' => $taxRate,
            'tax_amount' => round($taxAmount, 2),
            'total_cost' => round($totalCost, 2),
            'insurance_required' => $data['insurance_required'],
        ];
    }

    /**
     * الحصول على السعر الأساسي
     */
    private function getBaseRate($mode, $weight, $volume)
    {
        $rates = [
            'sea' => 2.5, // $ per kg
            'air' => 8.0, // $ per kg
            'land' => 3.5, // $ per kg
        ];

        return $rates[$mode] ?? $rates['sea'];
    }

    /**
     * حساب وقت النقل
     */
    private function calculateTransitTime($distance, $mode)
    {
        $speeds = [
            'sea' => 25, // nautical miles per hour
            'air' => 800, // km per hour
            'land' => 80, // km per hour
        ];

        $speed = $speeds[$mode] ?? $speeds['sea'];
        
        if ($mode === 'sea') {
            $distance = $distance * 0.539957; // Convert to nautical miles
        }

        $hours = $distance / $speed;
        return ceil($hours / 24); // Convert to days
    }

    /**
     * حفظ الحساب في قاعدة البيانات
     */
    private function saveCalculation($calculation, $user)
    {
        return CostCalculation::create(array_merge($calculation, [
            'user_id' => $user->id,
            'reference_number' => 'CALC-' . strtoupper(Str::random(8)),
            'status' => 'calculated',
            'saved_at' => now(),
        ]));
    }

    /**
     * الحصول على توصيات
     */
    private function getRecommendations($calculation)
    {
        $recommendations = [];

        // توصيات الوزن مقابل الحجم
        if ($calculation['volume'] && $calculation['weight']) {
            $volumetricWeight = $calculation['volume'] * 167;
            if ($volumetricWeight > $calculation['weight']) {
                $recommendations[] = [
                    'type' => 'volumetric_weight',
                    'message' => 'الوزن الحجمي أكبر من الوزن الفعلي - يُحسب على أساس الحجم',
                    'impact' => 'cost_increase'
                ];
            }
        }

        // توصيات التأمين
        if (!$calculation['insurance_required'] && $calculation['declared_value'] > 5000) {
            $recommendations[] = [
                'type' => 'insurance',
                'message' => 'نوصي بالتأمين للشحنات عالية القيمة',
                'impact' => 'risk_reduction'
            ];
        }

        return $recommendations;
    }

    /**
     * الحصول على البدائل
     */
    private function getAlternatives($request, $calculation)
    {
        // هنا يمكن حساب بدائل أخرى بأنواع شحن مختلفة
        return [];
    }

    // Placeholder methods for PDF/Excel generation
    private function generatePdf($calculation)
    {
        // Implementation for PDF generation
        return 'storage/exports/calculation.pdf';
    }

    private function generateExcel($calculation)
    {
        // Implementation for Excel generation
        return 'storage/exports/calculation.xlsx';
    }
}