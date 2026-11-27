<?php

namespace App\Policies;

use App\Models\BikeRack;
use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    public function viewAny(
        User $user,
        BikeRack $bikeRack,
    ): bool {
        return $user->can('view', $bikeRack);
    }

    public function view(
        User $user,
        Note $note,
    ): bool {
        return $user->can('view', $note->bikeRack);
    }

    public function create(
        User $user,
        BikeRack $bikeRack,
    ): bool {
        return $user->can('update', $bikeRack);
    }

    public function update(
        User $user,
        Note $note,
    ): bool {
        return $user->can('update', $note->bikeRack);
    }

    public function delete(
        User $user,
        Note $note,
    ): bool {
        return $user->can('update', $note->bikeRack);
    }
}
