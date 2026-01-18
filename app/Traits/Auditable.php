<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the trait
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLog::log('created', $model, [
                'after' => $model->getAttributes(),
            ], [
                'table' => $model->getTable(),
            ]);
        });

        static::updated(function ($model) {
            $changes = [
                'before' => $model->getOriginal(),
                'after' => $model->getAttributes(),
            ];

            AuditLog::log('updated', $model, $changes, [
                'table' => $model->getTable(),
                'dirty' => $model->getDirty(),
            ]);
        });

        static::deleted(function ($model) {
            AuditLog::log('deleted', $model, [
                'before' => $model->getOriginal(),
            ], [
                'table' => $model->getTable(),
            ]);
        });
    }

    /**
     * Get all audit logs for this model
     */
    public function auditLogs()
    {
        return AuditLog::forModel(get_class($this), $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent audit logs
     */
    public function recentAuditLogs(int $limit = 10)
    {
        return AuditLog::forModel(get_class($this), $this->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
