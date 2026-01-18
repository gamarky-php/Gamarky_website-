<?php

namespace App\Policies;

/**
 * Customs Operation Policy
 * 
 * Purpose: التحكم بصلاحيات عمليات التخليص الجمركي
 * RBAC: admin, customs_broker, customs_manager
 */

use App\Models\CustomsOperation;
use App\Models\User;

class CustomsOperationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'customs_manager', 'customs_broker']);
    }

    public function view(User $user, CustomsOperation $operation): bool
    {
        // المالك، المخلص، أو المدير
        return $user->id === $operation->user_id 
               || $user->id === $operation->broker_id
               || $user->hasAnyRole(['admin', 'customs_manager']);
    }

    public function create(User $user): bool
    {
        return true; // جميع المستخدمين
    }

    public function update(User $user, CustomsOperation $operation): bool
    {
        // لا يمكن تعديل العمليات الموافق عليها
        if (in_array($operation->status, ['approved', 'rejected'])) {
            return $user->hasRole('admin');
        }
        
        return $user->id === $operation->user_id 
               || $user->id === $operation->broker_id
               || $user->hasRole('admin');
    }

    public function delete(User $user, CustomsOperation $operation): bool
    {
        if ($operation->status === 'pending') {
            return $user->id === $operation->user_id || $user->hasRole('admin');
        }
        
        return $user->hasRole('admin');
    }

    public function approve(User $user, CustomsOperation $operation): bool
    {
        return $user->hasAnyRole(['admin', 'customs_manager', 'customs_broker']);
    }

    public function reject(User $user, CustomsOperation $operation): bool
    {
        return $user->hasAnyRole(['admin', 'customs_manager', 'customs_broker']);
    }

    public function assignBroker(User $user, CustomsOperation $operation): bool
    {
        return $user->hasAnyRole(['admin', 'customs_manager']);
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'customs_manager']);
    }
}
