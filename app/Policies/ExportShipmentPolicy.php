<?php

namespace App\Policies;

use App\Models\ExportShipment;
use App\Models\User;

class ExportShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user != null;
    }

    public function view(User $user, ExportShipment $shipment): bool
    {
        return $shipment->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user != null;
    }

    public function update(User $user, ExportShipment $shipment): bool
    {
        return $shipment->created_by === $user->id && $shipment->status === 'draft';
    }

    public function delete(User $user, ExportShipment $shipment): bool
    {
        return $shipment->created_by === $user->id && in_array($shipment->status, ['draft']);
    }

    public function createQuote(User $user, ExportShipment $shipment): bool
    {
        return $shipment->created_by === $user->id;
    }
}
