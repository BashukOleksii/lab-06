<?php

namespace App\Services;

use App\Models\BikeRack;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class NoteService
{
    public function create(
        User $user,
        BikeRack $bikeRack,
        array $data,
    ): Note {
        Gate::forUser($user)->authorize(
            'create',
            [Note::class, $bikeRack],
        );

        return $bikeRack->notes()->create($data);
    }

    public function update(
        User $user,
        Note $note,
        array $data,
    ): Note {
        Gate::forUser($user)->authorize(
            'update',
            $note,
        );

        $note->fill($data);
        $note->save();

        return $note;
    }

    public function delete(
        User $user,
        Note $note,
    ): void {
        Gate::forUser($user)->authorize(
            'delete',
            $note,
        );

        $note->delete();
    }
}
