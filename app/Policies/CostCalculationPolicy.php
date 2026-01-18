<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CostCalculation;

class CostCalculationPolicy
{
    /**
     * Determine if the user can view any cost calculations.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('costs.view');
    }

    /**
     * Determine if the user can view the cost calculation.
     */
    public function view(User $user, CostCalculation $calculation): bool
    {
        // Owner can always view their own calculations
        if ($user->id === $calculation->user_id) {
            return true;
        }

        return $user->can('costs.view');
    }

    /**
     * Determine if the user can create cost calculations.
     */
    public function create(User $user): bool
    {
        return $user->can('costs.calculate');
    }

    /**
     * Determine if the user can update the cost calculation.
     */
    public function update(User $user, CostCalculation $calculation): bool
    {
        // Can't update accepted or expired calculations
        if ($calculation->isAccepted() || $calculation->isExpired()) {
            return false;
        }

        // Owner can update their own calculations if draft or sent
        if ($user->id === $calculation->user_id && in_array($calculation->status, ['draft', 'sent'])) {
            return true;
        }

        return $user->can('costs.save');
    }

    /**
     * Determine if the user can delete the cost calculation.
     */
    public function delete(User $user, CostCalculation $calculation): bool
    {
        // Can't delete accepted calculations
        if ($calculation->isAccepted()) {
            return false;
        }

        // Owner can delete their own draft calculations
        if ($user->id === $calculation->user_id && $calculation->status === 'draft') {
            return true;
        }

        return $user->can('costs.save');
    }

    /**
     * Determine if the user can approve the cost calculation.
     */
    public function approve(User $user, CostCalculation $calculation): bool
    {
        return $user->can('costs.approve');
    }

    /**
     * Determine if the user can save as quote or invoice.
     */
    public function saveAs(User $user, CostCalculation $calculation): bool
    {
        // Owner can save their own calculations
        if ($user->id === $calculation->user_id) {
            return $user->can('costs.save');
        }

        return $user->can('costs.save');
    }
}
