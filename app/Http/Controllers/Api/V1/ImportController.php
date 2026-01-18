<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\Port;
use App\Models\ShippingType;
use App\Services\ImportCostService;

class ImportController extends Controller
{
    /**
     * Get prefilled data for import calculator
     * البيانات المسبقة لحاسبة الاستيراد
     *
     * @return JsonResponse
     */
    public function prefill(): JsonResponse
    {
        try {
            $prefillData = [
                'countries' => Country::select('id', 'name', 'iso2')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'code' => $country->iso2,
                            'label' => $country->name . ' (' . $country->iso2 . ')'
                        ];
                    }),

                'shipping_types' => ShippingType::select('id', 'name', 'code')
                    ->get()
                    ->map(function ($type) {
                        return [
                            'id' => $type->id,
                            'name' => $type->name,
                            'code' => $type->code,
                            'description' => $type->name
                        ];
                    }),

                'common_calculations' => [
                    [
                        'title' => 'الكترونيات من الصين',
                        'description' => 'هواتف، أجهزة كمبيوتر، إكسسوارات',
                        'preset' => [
                            'country_from' => 'CN',
                            'shipping_type' => 'sea',
                            'estimated_tax_rate' => 15,
                            'category' => 'electronics'
                        ]
                    ],
                    [
                        'title' => 'ملابس من تركيا',
                        'description' => 'أزياء، أحذية، إكسسوارات',
                        'preset' => [
                            'country_from' => 'TR',
                            'shipping_type' => 'land',
                            'estimated_tax_rate' => 10,
                            'category' => 'clothing'
                        ]
                    ],
                    [
                        'title' => 'قطع غيار السيارات',
                        'description' => 'من ألمانيا واليابان',
                        'preset' => [
                            'country_from' => 'DE',
                            'shipping_type' => 'sea',
                            'estimated_tax_rate' => 20,
                            'category' => 'auto_parts'
                        ]
                    ],
                    [
                        'title' => 'مواد غذائية',
                        'description' => 'مكسرات، توابل، معلبات',
                        'preset' => [
                            'shipping_type' => 'air',
                            'estimated_tax_rate' => 5,
                            'category' => 'food'
                        ]
                    ]
                ],

                'exchange_rates' => app(ImportCostService::class)->getExchangeRates(),

                'default_settings' => [
                    'currency' => 'SAR',
                    'weight_unit' => 'kg',
                    'dimension_unit' => 'cm',
                    'default_insurance_rate' => 2.5,
                    'default_handling_fee' => 50,
                    'vat_rate' => 15
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $prefillData,
                'message' => 'تم جلب البيانات المسبقة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب البيانات المسبقة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compute import costs and taxes
     * حساب تكاليف الاستيراد والضرائب - يستخدم نفس المنطق في صفحة الويب
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function compute(Request $request): JsonResponse
    {
        try {
            // التحقق من صحة البيانات - نفس validation قواعد صفحة الويب
            $validator = Validator::make($request->all(), [
                'origin_country' => 'required|string|max:2',
                'origin_port' => 'nullable|exists:ports,id',
                'destination_port' => 'required|exists:ports,id', 
                'shipping_type' => 'required|exists:shipping_types,id',
                'product_value' => 'required|numeric|min:1',
                'product_currency' => 'required|string|in:USD,EUR,GBP,SAR,CNY,TRY,JPY',
                'weight' => 'required|numeric|min:0.1',
                'dimensions' => 'nullable|array',
                'product_category' => 'nullable|string',
                'add_insurance' => 'boolean',
                'insurance_rate' => 'nullable|numeric|min:0|max:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // تحضير البيانات بنفس الطريقة المستخدمة في صفحة الويب
            $data = [
                'country_from' => $request->input('origin_country'),
                'country_to' => 'SA', // دائماً إلى السعودية
                'port_from' => (int) $request->input('origin_port', 1),
                'port_to' => (int) $request->input('destination_port', 1),
                'shipping_type' => (int) $request->input('shipping_type', 1),
                'product_value' => (float) $request->input('product_value', 0),
                'product_currency' => (string) $request->input('product_currency', 'USD'),
                'weight' => (float) $request->input('weight', 0),
                'dimensions' => $request->input('dimensions', []),
                'product_category' => (string) $request->input('product_category', 'general'),
                'add_insurance' => (bool) $request->input('add_insurance', false),
                'insurance_rate' => (float) $request->input('insurance_rate', 2.5)
            ];

            // استدعاء نفس Service المستخدم في صفحة الويب
            $importCostService = app(\App\Services\ImportCostService::class);
            $result = $importCostService->calculate($data);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'تم حساب التكاليف بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حساب التكاليف',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}