<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Helper closure to safely count rows, returns 0 on any error or missing table
        $safeCount = function (string $table, array $where = []) {
            try {
                if (! Schema::hasTable($table)) {
                    return 0;
                }

                $query = DB::table($table);

                foreach ($where as $col => $val) {
                    $query->where($col, $val);
                }

                return $query->count();
            } catch (\Throwable $e) {
                // If structure differs or column missing, fallback to 0
                return 0;
            }
        };

        $stats = [
            'import' => [
                'total'     => $safeCount('import_requests'),
                'active'    => $safeCount('import_requests', ['status' => 'active']),
                'completed' => $safeCount('import_requests', ['status' => 'done']),
                'avg_cost'  => null,
            ],
            'export' => [
                'total'  => $safeCount('export_requests'),
                'active' => $safeCount('export_requests', ['status' => 'active']),
                'top_goods' => null,
            ],
            'mfg' => [
                'factories' => $safeCount('factories', ['active' => 1]),
                'quotes'    => $safeCount('mfg_quotes'),
                'special'   => $safeCount('mfg_quotes', ['special' => 1]),
            ],
            'clearance' => [
                'requests' => $safeCount('clearance_requests'),
            ],
            'shipping' => [
                'quotes' => $safeCount('shipping_quotes'),
                'trend'  => null,
            ],
            'agent' => [
                'active' => $safeCount('agents', ['active' => 1]),
                'needs'  => $safeCount('agents', ['needs_agent' => 1]),
            ],
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
