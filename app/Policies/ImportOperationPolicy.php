<?php

namespace App\Policies;

/**
 * Import Operation Policy
 * 
 * Purpose: التحكم بصلاحيات عمليات الاستيراد
 * RBAC: admin, manager, user
 */

use App\Models\ImportOperation;
use App\Models\User;

class ImportOperationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin و Manager يمكنهم رؤية كل العمليات
        return $user->hasAnyRole(['admin', 'manager', 'import_manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ImportOperation $importOperation): bool
    {
        // صاحب العملية أو Admin/Manager
        return $user->id === $importOperation->user_id 
               || $user->hasAnyRole(['admin', 'manager', 'import_manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // جميع المستخدمين المصادق عليهم
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ImportOperation $importOperation): bool
    {
        // صاحب العملية أو Admin فقط إذا كانت قيد الانتظار
        if ($importOperation->status === 'completed') {
            return $user->hasRole('admin');
        }
        
        return $user->id === $importOperation->user_id 
               || $user->hasAnyRole(['admin', 'import_manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImportOperation $importOperation): bool
    {
        // Admin أو صاحب العملية إذا كانت قيد الانتظار
        if ($importOperation->status === 'pending') {
            return $user->id === $importOperation->user_id 
                   || $user->hasRole('admin');
        }
        
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImportOperation $importOperation): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImportOperation $importOperation): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve the operation.
     */
    public function approve(User $user, ImportOperation $importOperation): bool
    {
        return $user->hasAnyRole(['admin', 'import_manager']);
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'import_manager']);
    }
}
