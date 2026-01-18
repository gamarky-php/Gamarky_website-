<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditLogsController extends Controller
{
    /**
     * Get audit logs with filters
     * GET /api/audit-logs
     */
    public function index(Request $request): JsonResponse
    {
        $query = AuditLog::with('user:id,name,email')
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->action($request->action);
        }

        // Filter by model
        if ($request->filled('model_type')) {
            $query->forModel($request->model_type, $request->model_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }

        $logs = $query->paginate($request->per_page ?? 50);

        return response()->json($logs);
    }

    /**
     * Get audit log details
     * GET /api/audit-logs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return response()->json([
            'id' => $log->id,
            'user' => $log->user ? [
                'id' => $log->user->id,
                'name' => $log->user->name,
                'email' => $log->user->email,
            ] : null,
            'action' => $log->action,
            'formatted_action' => $log->formatted_action,
            'model_type' => $log->model_type,
            'model_id' => $log->model_id,
            'before_hash' => $log->before_hash,
            'after_hash' => $log->after_hash,
            'changes' => $log->changes,
            'changed_fields' => $log->getChangedFields(),
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'metadata' => $log->metadata,
            'created_at' => $log->created_at->toDateTimeString(),
        ]);
    }

    /**
     * Get audit logs for specific model
     * GET /api/audit-logs/model/{type}/{id}
     */
    public function forModel(string $type, int $id): JsonResponse
    {
        $modelClass = 'App\\Models\\' . $type;

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid model type'], 400);
        }

        $logs = AuditLog::forModel($modelClass, $id)
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }

    /**
     * Get audit statistics
     * GET /api/audit-logs/stats
     */
    public function stats(Request $request): JsonResponse
    {
        $days = $request->input('days', 30);

        $stats = [
            'total_logs' => AuditLog::recent($days)->count(),
            'by_action' => AuditLog::recent($days)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'by_user' => AuditLog::recent($days)
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->with('user:id,name')
                ->get()
                ->pluck('count', 'user.name'),
            'by_model' => AuditLog::recent($days)
                ->selectRaw('model_type, COUNT(*) as count')
                ->groupBy('model_type')
                ->pluck('count', 'model_type'),
            'recent_activity' => AuditLog::recent(7)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date'),
        ];

        return response()->json($stats);
    }

    /**
     * Get my audit logs
     * GET /api/audit-logs/my-activity
     */
    public function myActivity(): JsonResponse
    {
        $logs = AuditLog::byUser(auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return response()->json($logs);
    }

    /**
     * Export audit logs
     * GET /api/audit-logs/export
     */
    public function export(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'format' => 'in:csv,json',
        ]);

        $logs = AuditLog::whereBetween('created_at', [$validated['from_date'], $validated['to_date']])
            ->with('user:id,name,email')
            ->get();

        if ($validated['format'] === 'csv') {
            // Return CSV download
            return response()->json([
                'message' => 'CSV export not implemented yet',
            ]);
        }

        return response()->json([
            'count' => $logs->count(),
            'logs' => $logs,
        ]);
    }
}
