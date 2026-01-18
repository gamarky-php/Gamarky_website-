<?php

namespace App\Policies;

/**
 * Manufacturing Operation Policy
 * 
 * Purpose: التحكم بصلاحيات عمليات التصنيع
 * RBAC: admin, manufacturing_manager, production_user
 */

use App\Models\ManufacturingOperation;
use App\Models\User;

class ManufacturingOperationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'manufacturing_manager']);
    }

    public function view(User $user, ManufacturingOperation $operation): bool
    {
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'manufacturing_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manufacturing_manager', 'production_user']);
    }

    public function update(User $user, ManufacturingOperation $operation): bool
    {
        // لا يمكن تعديل العمليات المكتملة إلا من Admin
        if ($operation->status === 'completed') {
            return $user->hasRole('admin');
        }
        
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'manufacturing_manager']);
    }

    public function delete(User $user, ManufacturingOperation $operation): bool
    {
        // يمكن حذف المسودات فقط
        if ($operation->status === 'draft') {
            return $user->id === $operation->user_id 
                   || $user->hasRole('admin');
        }
        
        return $user->hasRole('admin');
    }

    public function restore(User $user, ManufacturingOperation $operation): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, ManufacturingOperation $operation): bool
    {
        return $user->hasRole('admin');
    }

    public function calculateCosts(User $user, ManufacturingOperation $operation): bool
    {
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'manufacturing_manager']);
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'manufacturing_manager']);
    }
}
