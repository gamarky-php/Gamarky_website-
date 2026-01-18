<?php

namespace App\Policies;

/**
 * Container Operation Policy
 * 
 * Purpose: التحكم بصلاحيات عمليات الحاويات
 * RBAC: admin, shipping_manager, logistics_user
 */

use App\Models\ContainerOperation;
use App\Models\User;

class ContainerOperationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'shipping_manager', 'logistics_manager']);
    }

    public function view(User $user, ContainerOperation $operation): bool
    {
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'shipping_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'shipping_manager', 'logistics_user']);
    }

    public function update(User $user, ContainerOperation $operation): bool
    {
        // لا يمكن تعديل الحاويات المسلمة
        if ($operation->status === 'delivered') {
            return $user->hasRole('admin');
        }
        
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'shipping_manager']);
    }

    public function delete(User $user, ContainerOperation $operation): bool
    {
        if (in_array($operation->status, ['pending', 'booked'])) {
            return $user->id === $operation->user_id || $user->hasRole('admin');
        }
        
        return $user->hasRole('admin');
    }

    public function track(User $user, ContainerOperation $operation): bool
    {
        // يمكن للجميع تتبع حاوياتهم
        return $user->id === $operation->user_id 
               || $user->hasAnyRole(['admin', 'shipping_manager']);
    }

    public function updateTracking(User $user, ContainerOperation $operation): bool
    {
        return $user->hasAnyRole(['admin', 'shipping_manager', 'logistics_user']);
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'shipping_manager']);
    }
}
