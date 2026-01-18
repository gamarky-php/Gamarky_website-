<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Cost Calculator API Controller
 * 
 * Handles cost calculations for:
 * - Import operations
 * - Export operations
 * - Manufacturing operations
 * 
 * Features:
 * - Real-time cost calculation
 * - Scenario saving
 * - PDF/Excel export
 * - KPIs generation
 */
class CostCalculatorController extends Controller
{
    /**
     * Calculate import costs
     * 
     * POST /api/costs/calculate
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'module' => 'required|in:import,export,manufacturing',
            'currency' => 'required|string|size:3',
            'inputs' => 'required|array',
            'items' => 'required|array',
            'items.*.key' => 'required|string',
            'items.*.value' => 'required|numeric|min:0',
            'margin_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculate based on module
        $result = match($validated['module']) {
            'import' => $this->calculateImport($validated),
            'export' => $this->calculateExport($validated),
            'manufacturing' => $this->calculateManufacturing($validated),
        };

        // Save to database
        $refCode = $this->generateRefCode($validated['module']);
        $calculationId = $this->saveCalculation($refCode, $validated, $result);

        return response()->json([
            'success' => true,
            'ref_code' => $refCode,
            'calculation_id' => $calculationId,
            'module' => $validated['module'],
            'totals' => $result['totals'],
            'kpis' => $result['kpis'],
            'breakdown' => $result['breakdown'] ?? null,
        ]);
    }

    /**
     * Calculate import costs
     */
    protected function calculateImport(array $data): array
    {
        $inputs = $data['inputs'];
        $items = collect($data['items'])->keyBy('key');
        
        // Base calculations
        $purchasePrice = $inputs['purchase_price'] ?? 0;
        $internationalFreight = $items->get('international_freight')['value'] ?? 0;
        $insurance = $items->get('insurance')['value'] ?? 0;
        $localPortFees = $items->get('local_port_fees')['value'] ?? 0;
        $customsDuties = $items->get('customs_duties')['value'] ?? 0;
        $clearanceFees = $items->get('clearance_fees')['value'] ?? 0;
        $inlandTransport = $items->get('inland_transport')['value'] ?? 0;
        
        // Total logistics
        $totalLogistics = $internationalFreight + $insurance + $localPortFees + $inlandTransport;
        
        // Total customs & clearance
        $totalCustoms = $customsDuties + $clearanceFees;
        
        // COGS (Cost of Goods Sold)
        $cogs = $purchasePrice + $totalLogistics + $totalCustoms;
        
        // Margin calculation
        $marginPercent = $data['margin_percent'] ?? 0;
        $marginValue = ($cogs * $marginPercent) / 100;
        $sellPrice = $cogs + $marginValue;
        
        // KPIs
        $dutyRatio = $purchasePrice > 0 ? ($customsDuties / $purchasePrice) * 100 : 0;
        $logisticsShare = $cogs > 0 ? ($totalLogistics / $cogs) * 100 : 0;
        $leadTimeDays = $this->estimateLeadTime($inputs['origin_country'] ?? 'CN', $inputs['destination_port'] ?? 'EG-ALY');
        
        return [
            'totals' => [
                'cogs' => round($cogs, 2),
                'margin_value' => round($marginValue, 2),
                'sell_price' => round($sellPrice, 2),
            ],
            'kpis' => [
                'duty_ratio' => round($dutyRatio, 1),
                'logistics_share' => round($logisticsShare, 1),
                'lead_time_days' => $leadTimeDays,
            ],
            'breakdown' => [
                'purchase_price' => $purchasePrice,
                'total_logistics' => round($totalLogistics, 2),
                'total_customs' => round($totalCustoms, 2),
                'international_freight' => $internationalFreight,
                'insurance' => $insurance,
                'local_port_fees' => $localPortFees,
                'customs_duties' => $customsDuties,
                'clearance_fees' => $clearanceFees,
                'inland_transport' => $inlandTransport,
            ]
        ];
    }

    /**
     * Calculate export costs
     */
    protected function calculateExport(array $data): array
    {
        $inputs = $data['inputs'];
        $items = collect($data['items'])->keyBy('key');
        
        // Base calculations
        $productionCost = $inputs['production_cost'] ?? 0;
        $packagingCost = $items->get('packaging')['value'] ?? 0;
        $localTransport = $items->get('local_transport')['value'] ?? 0;
        $exportClearance = $items->get('export_clearance')['value'] ?? 0;
        $internationalFreight = $items->get('international_freight')['value'] ?? 0;
        $insurance = $items->get('insurance')['value'] ?? 0;
        $certifications = $items->get('certifications')['value'] ?? 0;
        
        // Total costs
        $totalPreShip = $productionCost + $packagingCost + $localTransport;
        $totalExport = $exportClearance + $certifications;
        $totalLogistics = $internationalFreight + $insurance;
        
        // COGS
        $cogs = $totalPreShip + $totalExport + $totalLogistics;
        
        // Margin
        $marginPercent = $data['margin_percent'] ?? 0;
        $marginValue = ($cogs * $marginPercent) / 100;
        $fobPrice = $cogs + $marginValue;
        
        // KPIs
        $competitivenessIndex = $this->calculateCompetitivenessIndex($fobPrice, $inputs['destination_country'] ?? 'AE');
        $logisticsShare = $cogs > 0 ? ($totalLogistics / $cogs) * 100 : 0;
        $certCostRatio = $productionCost > 0 ? ($certifications / $productionCost) * 100 : 0;
        
        return [
            'totals' => [
                'cogs' => round($cogs, 2),
                'margin_value' => round($marginValue, 2),
                'fob_price' => round($fobPrice, 2),
            ],
            'kpis' => [
                'competitiveness_index' => round($competitivenessIndex, 1),
                'logistics_share' => round($logisticsShare, 1),
                'cert_cost_ratio' => round($certCostRatio, 1),
            ],
            'breakdown' => [
                'production_cost' => $productionCost,
                'total_pre_ship' => round($totalPreShip, 2),
                'total_export' => round($totalExport, 2),
                'total_logistics' => round($totalLogistics, 2),
            ]
        ];
    }

    /**
     * Calculate manufacturing costs
     */
    protected function calculateManufacturing(array $data): array
    {
        $inputs = $data['inputs'];
        $items = collect($data['items'])->keyBy('key');
        
        // Direct costs
        $rawMaterials = $items->get('raw_materials')['value'] ?? 0;
        $directLabor = $items->get('direct_labor')['value'] ?? 0;
        $directUtilities = $items->get('direct_utilities')['value'] ?? 0;
        
        // Indirect costs
        $indirectLabor = $items->get('indirect_labor')['value'] ?? 0;
        $factoryOverhead = $items->get('factory_overhead')['value'] ?? 0;
        $qualityControl = $items->get('quality_control')['value'] ?? 0;
        $depreciation = $items->get('depreciation')['value'] ?? 0;
        
        // Totals
        $totalDirect = $rawMaterials + $directLabor + $directUtilities;
        $totalIndirect = $indirectLabor + $factoryOverhead + $qualityControl + $depreciation;
        $totalManufacturingCost = $totalDirect + $totalIndirect;
        
        // Units
        $unitsProduced = $inputs['units_produced'] ?? 1;
        $costPerUnit = $unitsProduced > 0 ? $totalManufacturingCost / $unitsProduced : 0;
        
        // Margin
        $marginPercent = $data['margin_percent'] ?? 0;
        $marginValue = ($totalManufacturingCost * $marginPercent) / 100;
        $sellPrice = $totalManufacturingCost + $marginValue;
        $pricePerUnit = $unitsProduced > 0 ? $sellPrice / $unitsProduced : 0;
        
        // KPIs
        $overheadRate = $totalDirect > 0 ? ($totalIndirect / $totalDirect) * 100 : 0;
        $materialShare = $totalManufacturingCost > 0 ? ($rawMaterials / $totalManufacturingCost) * 100 : 0;
        $laborShare = $totalManufacturingCost > 0 ? (($directLabor + $indirectLabor) / $totalManufacturingCost) * 100 : 0;
        
        return [
            'totals' => [
                'total_manufacturing_cost' => round($totalManufacturingCost, 2),
                'cost_per_unit' => round($costPerUnit, 2),
                'margin_value' => round($marginValue, 2),
                'sell_price' => round($sellPrice, 2),
                'price_per_unit' => round($pricePerUnit, 2),
            ],
            'kpis' => [
                'overhead_rate' => round($overheadRate, 1),
                'material_share' => round($materialShare, 1),
                'labor_share' => round($laborShare, 1),
            ],
            'breakdown' => [
                'total_direct' => round($totalDirect, 2),
                'total_indirect' => round($totalIndirect, 2),
                'raw_materials' => $rawMaterials,
                'direct_labor' => $directLabor,
                'direct_utilities' => $directUtilities,
                'indirect_labor' => $indirectLabor,
                'factory_overhead' => $factoryOverhead,
                'quality_control' => $qualityControl,
                'depreciation' => $depreciation,
            ]
        ];
    }

    /**
     * Generate reference code
     */
    protected function generateRefCode(string $module): string
    {
        $prefix = match($module) {
            'import' => 'IMP',
            'export' => 'EXP',
            'manufacturing' => 'MFG',
        };
        
        $year = date('Y');
        
        // Get last number for this module and year
        $lastNumber = DB::table('cost_calculations')
            ->where('ref_code', 'like', "{$prefix}-CC-{$year}-%")
            ->orderBy('id', 'desc')
            ->value('ref_code');
        
        if ($lastNumber) {
            $number = intval(substr($lastNumber, -6)) + 1;
        } else {
            $number = 1;
        }
        
        return sprintf('%s-CC-%s-%06d', $prefix, $year, $number);
    }

    /**
     * Save calculation to database
     */
    protected function saveCalculation(string $refCode, array $data, array $result): int
    {
        return DB::table('cost_calculations')->insertGetId([
            'ref_code' => $refCode,
            'module' => $data['module'],
            'user_id' => auth()->id() ?? null,
            'title' => 'حساب ' . match($data['module']) {
                'import' => 'استيراد',
                'export' => 'تصدير',
                'manufacturing' => 'تصنيع',
            },
            'currency' => $data['currency'],
            'inputs' => json_encode($data['inputs']),
            'items' => json_encode($data['items']),
            'margin_percent' => $data['margin_percent'] ?? 0,
            'totals' => json_encode($result['totals']),
            'metadata' => json_encode([
                'kpis' => $result['kpis'],
                'breakdown' => $result['breakdown'] ?? []
            ]),
            'final_total' => $result['totals']['sell_price'] ?? $result['totals']['fob_price'] ?? $result['totals']['price_per_unit'] ?? 0,
            'saved_as' => 'scenario',
            'status' => 'draft',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Estimate lead time based on origin and destination
     */
    protected function estimateLeadTime(string $origin, string $destination): int
    {
        // Simple estimation logic
        $distances = [
            'CN-EG' => 26,
            'CN-SA' => 22,
            'CN-AE' => 20,
            'US-EG' => 18,
            'US-SA' => 16,
            'EU-EG' => 12,
            'EU-SA' => 10,
        ];
        
        $key = substr($origin, 0, 2) . '-' . substr($destination, 0, 2);
        
        return $distances[$key] ?? 25; // Default 25 days
    }

    /**
     * Calculate competitiveness index
     */
    protected function calculateCompetitivenessIndex(float $price, string $destination): float
    {
        // Market averages (simplified)
        $marketAverages = [
            'AE' => 15000,
            'SA' => 14500,
            'EG' => 12000,
            'US' => 18000,
        ];
        
        $countryCode = substr($destination, 0, 2);
        $marketAvg = $marketAverages[$countryCode] ?? 15000;
        
        if ($marketAvg == 0) return 0;
        
        // Index: lower price = higher competitiveness
        return (($marketAvg - $price) / $marketAvg) * 100;
    }

    /**
     * Get saved calculations
     * 
     * GET /api/costs/saved
     */
    public function getSaved(Request $request)
    {
        $query = DB::table('cost_calculations')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');
        
        if ($request->has('module')) {
            $query->where('module', $request->module);
        }
        
        if ($request->has('search')) {
            $query->where('ref_code', 'like', '%' . $request->search . '%');
        }
        
        $calculations = $query->paginate(20);
        
        // Decode metadata for each item
        $items = collect($calculations->items())->map(function($item) {
            $metadata = json_decode($item->metadata ?? '{}', true);
            $item->kpis = $metadata['kpis'] ?? [];
            $item->breakdown = $metadata['breakdown'] ?? [];
            unset($item->metadata);
            return $item;
        })->toArray();
        
        return response()->json([
            'success' => true,
            'data' => $items,
            'pagination' => [
                'total' => $calculations->total(),
                'per_page' => $calculations->perPage(),
                'current_page' => $calculations->currentPage(),
                'last_page' => $calculations->lastPage(),
            ]
        ]);
    }

    /**
     * Get calculation by ID
     * 
     * GET /api/costs/{id}
     */
    public function getById(int $id)
    {
        $calculation = DB::table('cost_calculations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        if (!$calculation) {
            return response()->json([
                'success' => false,
                'error' => 'Calculation not found'
            ], 404);
        }
        
        // Decode JSON fields
        $calculation->inputs = json_decode($calculation->inputs, true);
        $calculation->items = json_decode($calculation->items, true);
        $calculation->totals = json_decode($calculation->totals, true);
        
        $metadata = json_decode($calculation->metadata ?? '{}', true);
        $calculation->kpis = $metadata['kpis'] ?? [];
        $calculation->breakdown = $metadata['breakdown'] ?? [];
        unset($calculation->metadata);
        
        return response()->json([
            'success' => true,
            'data' => $calculation
        ]);
    }

    /**
     * Delete calculation
     * 
     * DELETE /api/costs/{id}
     */
    public function delete(int $id)
    {
        $deleted = DB::table('cost_calculations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'error' => 'Calculation not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Calculation deleted successfully'
        ]);
    }

    /**
     * Export to PDF
     * 
     * GET /api/costs/{id}/pdf
     */
    public function exportPdf(int $id)
    {
        $calculation = DB::table('cost_calculations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        if (!$calculation) {
            return response()->json(['error' => 'Not found'], 404);
        }
        
        // TODO: Generate PDF
        return response()->json([
            'success' => true,
            'message' => 'PDF export coming soon',
            'download_url' => '#'
        ]);
    }

    /**
     * Export to Excel
     * 
     * GET /api/costs/{id}/excel
     */
    public function exportExcel(int $id)
    {
        $calculation = DB::table('cost_calculations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        if (!$calculation) {
            return response()->json(['error' => 'Not found'], 404);
        }
        
        // TODO: Generate Excel
        return response()->json([
            'success' => true,
            'message' => 'Excel export coming soon',
            'download_url' => '#'
        ]);
    }
}
