<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can always view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        return $user->can('users.view');
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile (limited fields)
        if ($user->id === $model->id) {
            return true;
        }

        return $user->can('users.edit');
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Can't delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Can't delete users with admin role (unless you're admin)
        if ($model->hasRole('admin') && !$user->hasRole('admin')) {
            return false;
        }

        return $user->can('users.delete');
    }

    /**
     * Determine if the user can assign roles.
     */
    public function assignRole(User $user, User $model): bool
    {
        // Can't assign role to yourself
        if ($user->id === $model->id) {
            return false;
        }

        return $user->can('roles.assign');
    }

    /**
     * Determine if the user can manage users.
     */
    public function manage(User $user): bool
    {
        return $user->can('users.manage');
    }
}
