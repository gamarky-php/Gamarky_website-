<?php

namespace App\Policies;

use App\Models\ExportQuote;
use App\Models\User;

class ExportQuotePolicy
{
    public function viewAny(User $user): bool
    {
        return $user != null;
    }

    public function view(User $user, ExportQuote $quote): bool
    {
        return $quote->shipment->created_by === $user->id;
    }

    public function export(User $user, ExportQuote $quote): bool
    {
        return $this->view($user, $quote);
    }

    public function send(User $user, ExportQuote $quote): bool
    {
        return $quote->shipment->created_by === $user->id && $quote->status === 'draft';
    }
}
