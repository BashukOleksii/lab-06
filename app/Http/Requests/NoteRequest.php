<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class NoteRequest extends FormRequest
{
    protected function fieldRules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:120',
            ],

            'body' => [
                'required',
                'string',
                'max:2000',
            ],
        ];
    }

    public function noteData(): array
    {
        return $this->safe()->only(
            array_keys($this->fieldRules())
        );
    }
}
