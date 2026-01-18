<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClearanceJob;

class ClearanceJobPolicy
{
    /**
     * Determine if the user can view any clearance jobs.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('clearance.view');
    }

    /**
     * Determine if the user can view the clearance job.
     */
    public function view(User $user, ClearanceJob $job): bool
    {
        // Client can always view their own jobs
        if ($user->id === $job->client_id) {
            return true;
        }

        // Assigned broker can view the job
        if ($job->broker_id && $user->id === $job->broker_id) {
            return true;
        }

        // Others need clearance.view permission
        return $user->can('clearance.view');
    }

    /**
     * Determine if the user can create clearance jobs.
     */
    public function create(User $user): bool
    {
        return $user->can('clearance.create');
    }

    /**
     * Determine if the user can update the clearance job.
     */
    public function update(User $user, ClearanceJob $job): bool
    {
        // Client can update if job is pending
        if ($user->id === $job->client_id && $job->status === 'pending') {
            return true;
        }

        // Assigned broker can manage the job
        if ($job->broker_id && $user->id === $job->broker_id) {
            return $user->can('clearance.manage');
        }

        // Others need clearance.manage permission
        return $user->can('clearance.manage');
    }

    /**
     * Determine if the user can delete the clearance job.
     */
    public function delete(User $user, ClearanceJob $job): bool
    {
        // Can't delete jobs that are not pending or cancelled
        if (!in_array($job->status, ['pending', 'cancelled'])) {
            return false;
        }

        // Client can delete their own pending jobs
        if ($user->id === $job->client_id && $job->status === 'pending') {
            return true;
        }

        // Others need clearance.manage permission
        return $user->can('clearance.manage');
    }

    /**
     * Determine if the user can approve the clearance job.
     */
    public function approve(User $user, ClearanceJob $job): bool
    {
        return $user->can('clearance.approve');
    }
}
