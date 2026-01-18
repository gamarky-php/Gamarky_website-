<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Broker;

class BrokerPolicy
{
    /**
     * Determine if the user can view any brokers.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('brokers.view');
    }

    /**
     * Determine if the user can view the broker.
     */
    public function view(User $user, Broker $broker): bool
    {
        return $user->can('brokers.view');
    }

    /**
     * Determine if the user can create brokers.
     */
    public function create(User $user): bool
    {
        return $user->can('brokers.create');
    }

    /**
     * Determine if the user can update the broker.
     */
    public function update(User $user, Broker $broker): bool
    {
        return $user->can('brokers.edit');
    }

    /**
     * Determine if the user can delete the broker.
     */
    public function delete(User $user, Broker $broker): bool
    {
        // Can't delete broker with active clearance jobs
        if ($broker->clearanceJobs()->whereNotIn('status', ['cleared', 'released', 'cancelled'])->exists()) {
            return false;
        }

        return $user->can('brokers.delete');
    }

    /**
     * Determine if the user can search brokers.
     */
    public function search(User $user): bool
    {
        return $user->can('brokers.search');
    }

    /**
     * Determine if the user can review brokers.
     */
    public function review(User $user, Broker $broker): bool
    {
        return $user->can('brokers.review');
    }

    /**
     * Determine if the user can approve brokers.
     */
    public function approve(User $user, Broker $broker): bool
    {
        return $user->can('brokers.approve');
    }
}
