<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BikeRackRequest extends FormRequest
{
    protected function fieldRules(): array
    {
        return [
            'district_id' => ['bail', 'required', 'integer', 'exists:districts,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'slot_count' => ['required', 'integer', 'between:1,1000'],
            'scheduled_opening_on' => ['required', 'date_format:Y-m-d'],
        ];
    }

    public function bikeRackData(): array
    {
        return $this->safe()->only(array_keys($this->fieldRules()));
    }
}
