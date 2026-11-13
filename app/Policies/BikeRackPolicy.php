<?php

namespace App\Policies;

use App\Models\BikeRack;
use App\Models\User;

class BikeRackPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BikeRack $bikeRack): bool
    {
        return $this->owns($user, $bikeRack);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BikeRack $bikeRack): bool
    {
        return $this->owns($user, $bikeRack);
    }

    public function delete(User $user, BikeRack $bikeRack): bool
    {
        return $this->owns($user, $bikeRack);
    }

    public function open(User $user, BikeRack $bikeRack): bool
    {
        return $this->owns($user, $bikeRack);
    }

    private function owns(User $user, BikeRack $bikeRack): bool
    {
        return $user->id === (int) $bikeRack->owner_id;
    }
}
