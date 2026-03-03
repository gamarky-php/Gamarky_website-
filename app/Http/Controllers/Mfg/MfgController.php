<?php

namespace App\Http\Controllers\Mfg;

use App\Http\Controllers\Controller;
use App\Models\BomItem;
use App\Models\MfgCostRun;
use App\Models\OverheadPool;
use App\Models\Product;
use App\Models\RoutingOp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MfgController extends Controller
{
    public function index()
    {
        $runs = MfgCostRun::with(['product', 'creator'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(15);

        return view('front.mfg.runs.index', compact('runs'));
    }

    public function create()
    {
        $products = Product::query()->latest()->take(50)->get();

        return view('front.manufacturing.calculator', [
            'products' => $products,
            'currencies' => ['USD', 'EUR', 'EGP', 'SAR', 'AED'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_size' => 'required|integer|min:1',
            'scrap_pct' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'fx_rate' => 'required|numeric|min:0',
            'margin_pct' => 'nullable|numeric|min:0|max:100',

            'bom_items' => 'nullable|array',
            'bom_items.*.material' => 'required|string|max:255',
            'bom_items.*.uom' => 'required|string|max:50',
            'bom_items.*.qty_per_batch' => 'required|numeric|min:0',
            'bom_items.*.unit_price' => 'required|numeric|min:0',
            'bom_items.*.scrap_pct' => 'nullable|numeric|min:0|max:100',

            'routing_ops' => 'nullable|array',
            'routing_ops.*.op_seq' => 'required|integer|min:1',
            'routing_ops.*.operation' => 'required|string|max:255',
            'routing_ops.*.setup_time_hr' => 'required|numeric|min:0',
            'routing_ops.*.run_time_hr' => 'required|numeric|min:0',
            'routing_ops.*.labor_rate' => 'required|numeric|min:0',
            'routing_ops.*.machine_rate' => 'required|numeric|min:0',

            'overhead_pools' => 'nullable|array',
            'overhead_pools.*.name' => 'required|string|max:255',
            'overhead_pools.*.basis' => 'required|in:machine_hour,labor_hour,material_pct',
            'overhead_pools.*.rate' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create Cost Run
            $run = MfgCostRun::create([
                'product_id' => $validated['product_id'],
                'batch_size' => $validated['batch_size'],
                'scrap_pct' => $validated['scrap_pct'] ?? 0,
                'currency' => $validated['currency'],
                'fx_rate' => $validated['fx_rate'],
                'margin_pct' => $validated['margin_pct'] ?? 20,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // Create BOM Items
            if (! empty($validated['bom_items'])) {
                foreach ($validated['bom_items'] as $item) {
                    BomItem::create([
                        'mfg_cost_run_id' => $run->id,
                        'material' => $item['material'],
                        'uom' => $item['uom'],
                        'qty_per_batch' => $item['qty_per_batch'],
                        'unit_price' => $item['unit_price'],
                        'scrap_pct' => $item['scrap_pct'] ?? 0,
                    ]);
                }
            }

            // Create Routing Operations
            if (! empty($validated['routing_ops'])) {
                foreach ($validated['routing_ops'] as $op) {
                    RoutingOp::create([
                        'mfg_cost_run_id' => $run->id,
                        'op_seq' => $op['op_seq'],
                        'operation' => $op['operation'],
                        'setup_time_hr' => $op['setup_time_hr'],
                        'run_time_hr' => $op['run_time_hr'],
                        'labor_rate' => $op['labor_rate'],
                        'machine_rate' => $op['machine_rate'],
                    ]);
                }
            }

            // Create Overhead Pools
            if (! empty($validated['overhead_pools'])) {
                foreach ($validated['overhead_pools'] as $pool) {
                    OverheadPool::create([
                        'mfg_cost_run_id' => $run->id,
                        'name' => $pool['name'],
                        'basis' => $pool['basis'],
                        'rate' => $pool['rate'],
                    ]);
                }
            }

            // Calculate Totals
            $run->load(['bomItems', 'ops', 'overheads']);

            $materialCost = $run->bomItems->sum('total_cost');
            $operationCost = $run->ops->sum('total_cost');
            $overheadCost = $run->overheads->sum(fn ($pool) => $pool->calculateCost($run));

            $totalCost = $materialCost + $operationCost + $overheadCost;
            $unitCost = $run->batch_size > 0 ? $totalCost / $run->batch_size : 0;

            $targetPrice = null;
            if ($run->margin_pct > 0) {
                $targetPrice = $unitCost / (1 - $run->margin_pct / 100);
            }

            // Create snapshot
            $snapshot = [
                'product' => $run->product->toArray(),
                'bom' => $run->bomItems->toArray(),
                'routing' => $run->ops->toArray(),
                'overheads' => $run->overheads->toArray(),
                'calculated_at' => now()->toIso8601String(),
            ];

            $run->update([
                'total_cost' => $totalCost,
                'unit_cost' => $unitCost,
                'target_price' => $targetPrice,
                'snapshot_json' => $snapshot,
            ]);

            DB::commit();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.cost_saved_successfully'),
                    'run_id' => $run->id,
                    'redirect' => route('mfg.runs.show', $run->id),
                ], 201);
            }

            return redirect()
                ->route('mfg.runs.show', $run->id)
                ->with('status', __('messages.cost_saved_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['error' => 'فشل حفظ البيانات: '.$e->getMessage()], 500);
            }

            return back()->withErrors(['error' => 'فشل حفظ البيانات'])->withInput();
        }
    }

    public function show($id)
    {
        $run = MfgCostRun::with(['product', 'bomItems', 'ops', 'overheads', 'quotes'])
            ->findOrFail($id);

        if ($run->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('front.mfg.run_show', compact('run'));
    }
}
