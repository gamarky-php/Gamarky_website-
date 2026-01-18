<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContainerQuote;

class ContainerQuotePolicy
{
    /**
     * Determine if the user can view any container quotes.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('containers.view');
    }

    /**
     * Determine if the user can view the container quote.
     */
    public function view(User $user, ContainerQuote $quote): bool
    {
        // Requester can always view their own quotes
        if ($quote->requester_id && $user->id === $quote->requester_id) {
            return true;
        }

        return $user->can('containers.view');
    }

    /**
     * Determine if the user can create container quotes.
     */
    public function create(User $user): bool
    {
        return $user->can('containers.quote');
    }

    /**
     * Determine if the user can update the container quote.
     */
    public function update(User $user, ContainerQuote $quote): bool
    {
        // Can't update expired or accepted quotes
        if (in_array($quote->status, ['expired', 'accepted'])) {
            return false;
        }

        return $user->can('containers.manage');
    }

    /**
     * Determine if the user can delete the container quote.
     */
    public function delete(User $user, ContainerQuote $quote): bool
    {
        // Can't delete accepted quotes
        if ($quote->status === 'accepted') {
            return false;
        }

        return $user->can('containers.manage');
    }

    /**
     * Determine if the user can accept the container quote.
     */
    public function accept(User $user, ContainerQuote $quote): bool
    {
        // Must be valid quote
        if (!$quote->isValid()) {
            return false;
        }

        return $user->can('containers.book');
    }
}
