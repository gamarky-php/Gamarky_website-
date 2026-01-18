<?php

namespace App\Services;

use App\Models\CostCalculation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CostService
{
    /**
     * Calculate import shipping cost
     *
     * @param array $data
     * @return array
     */
    public function calculateImport(array $data): array
    {
        $weight = $data['weight'] ?? 0;
        $volume = $data['volume'] ?? 0;
        $value = $data['value'] ?? 0;
        
        // Base rates (example)
        $oceanFreightRate = 50; // per CBM
        $customsDutyRate = 0.05; // 5%
        $vatRate = 0.15; // 15%
        $handlingFee = 500; // fixed
        
        // Calculations
        $oceanFreight = $volume * $oceanFreightRate;
        $customsDuty = $value * $customsDutyRate;
        $vat = ($value + $customsDuty) * $vatRate;
        $handling = $handlingFee;
        
        $total = $oceanFreight + $customsDuty + $vat + $handling;
        
        return [
            'ocean_freight' => round($oceanFreight, 2),
            'customs_duty' => round($customsDuty, 2),
            'vat' => round($vat, 2),
            'handling' => round($handling, 2),
            'total' => round($total, 2),
        ];
    }
    
    /**
     * Calculate export shipping cost
     *
     * @param array $data
     * @return array
     */
    public function calculateExport(array $data): array
    {
        $weight = $data['weight'] ?? 0;
        $volume = $data['volume'] ?? 0;
        $value = $data['value'] ?? 0;
        
        // Base rates (example)
        $airFreightRate = 100; // per kg
        $documentationFee = 300;
        $exportDutyRate = 0.02; // 2%
        $handlingFee = 400;
        
        // Calculations
        $airFreight = $weight * $airFreightRate;
        $exportDuty = $value * $exportDutyRate;
        $documentation = $documentationFee;
        $handling = $handlingFee;
        
        $total = $airFreight + $exportDuty + $documentation + $handling;
        
        return [
            'air_freight' => round($airFreight, 2),
            'export_duty' => round($exportDuty, 2),
            'documentation' => round($documentation, 2),
            'handling' => round($handling, 2),
            'total' => round($total, 2),
        ];
    }
    
    /**
     * Calculate manufacturing cost
     *
     * @param array $data
     * @return array
     */
    public function calculateManufacturing(array $data): array
    {
        $materialCost = $data['material_cost'] ?? 0;
        $laborCost = $data['labor_cost'] ?? 0;
        $overheadCost = $data['overhead_cost'] ?? 0;
        $quantity = $data['quantity'] ?? 1;
        
        $totalMaterialCost = $materialCost * $quantity;
        $totalLaborCost = $laborCost * $quantity;
        $totalOverheadCost = $overheadCost;
        
        $totalCost = $totalMaterialCost + $totalLaborCost + $totalOverheadCost;
        $unitCost = $quantity > 0 ? $totalCost / $quantity : 0;
        
        return [
            'material_cost' => round($totalMaterialCost, 2),
            'labor_cost' => round($totalLaborCost, 2),
            'overhead_cost' => round($totalOverheadCost, 2),
            'total_cost' => round($totalCost, 2),
            'unit_cost' => round($unitCost, 2),
            'quantity' => $quantity,
        ];
    }
    
    /**
     * Save cost calculation
     *
     * @param string $mode
     * @param array $input
     * @param array $breakdown
     * @param int|null $userId
     * @return CostCalculation
     */
    public function save(string $mode, array $input, array $breakdown, ?int $userId = null): CostCalculation
    {
        $refCode = $this->generateRefCode($mode);
        
        return CostCalculation::create([
            'user_id' => $userId ?? auth()->id(),
            'ref_code' => $refCode,
            'mode' => $mode,
            'input_data' => $input,
            'breakdown' => $breakdown,
            'total_cost' => $breakdown['total'] ?? $breakdown['total_cost'],
            'currency' => 'SAR',
        ]);
    }
    
    /**
     * Generate unique reference code
     *
     * @param string $mode
     * @return string
     */
    protected function generateRefCode(string $mode): string
    {
        $prefix = match($mode) {
            'import' => 'IMP',
            'export' => 'EXP',
            'manufacturing' => 'MFG',
            default => 'GEN',
        };
        
        $random = strtoupper(Str::random(8));
        $timestamp = now()->format('ymd');
        
        return "{$prefix}-{$timestamp}-{$random}";
    }
    
    /**
     * Get user calculation history
     *
     * @param int|null $userId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getHistory(?int $userId = null, int $limit = 10)
    {
        $userId = $userId ?? auth()->id();
        
        return CostCalculation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get calculation statistics
     *
     * @param int|null $userId
     * @return array
     */
    public function getStats(?int $userId = null): array
    {
        $userId = $userId ?? auth()->id();
        
        $totalCalculations = CostCalculation::where('user_id', $userId)->count();
        
        $byMode = CostCalculation::where('user_id', $userId)
            ->select('mode', DB::raw('count(*) as count'))
            ->groupBy('mode')
            ->pluck('count', 'mode')
            ->toArray();
        
        $averageCost = CostCalculation::where('user_id', $userId)
            ->avg('total_cost');
        
        $totalCost = CostCalculation::where('user_id', $userId)
            ->sum('total_cost');
        
        return [
            'total_calculations' => $totalCalculations,
            'by_mode' => $byMode,
            'average_cost' => round($averageCost ?? 0, 2),
            'total_cost' => round($totalCost ?? 0, 2),
        ];
    }
    
    /**
     * Export calculations to array
     *
     * @param int|null $userId
     * @return array
     */
    public function export(?int $userId = null): array
    {
        $userId = $userId ?? auth()->id();
        
        return CostCalculation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($calc) {
                return [
                    'ref_code' => $calc->ref_code,
                    'mode' => $calc->mode,
                    'total_cost' => $calc->total_cost,
                    'currency' => $calc->currency,
                    'created_at' => $calc->created_at->toDateTimeString(),
                    'breakdown' => $calc->breakdown,
                ];
            })
            ->toArray();
    }
}
