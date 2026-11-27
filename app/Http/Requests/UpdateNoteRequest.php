<?php

namespace App\Http\Requests;

use App\Models\Note;

class UpdateNoteRequest extends NoteRequest
{
    public function authorize(): bool
    {
        $note = $this->route('note');

        return $note instanceof Note
            && (
                $this->user()?->can(
                    'update',
                    $note,
                ) ?? false
            );
    }

    public function rules(): array
    {
        return array_map(
            static fn(array $rules): array =>
            ['sometimes', ...$rules],

            $this->fieldRules(),
        );
    }
}
