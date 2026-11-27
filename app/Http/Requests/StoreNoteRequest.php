<?php

namespace App\Http\Requests;

use App\Models\BikeRack;
use App\Models\Note;

class StoreNoteRequest extends NoteRequest
{
    public function authorize(): bool
    {
        $bikeRack = $this->route('bike_rack');

        return $bikeRack instanceof BikeRack
            && (
                $this->user()?->can(
                    'create',
                    [Note::class, $bikeRack],
                ) ?? false
            );
    }

    public function rules(): array
    {
        return $this->fieldRules();
    }
}
