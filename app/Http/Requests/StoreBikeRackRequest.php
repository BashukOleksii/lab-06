<?php

namespace App\Http\Requests;

use App\Models\BikeRack;

class StoreBikeRackRequest extends BikeRackRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', BikeRack::class) ?? false;
    }

    public function rules(): array
    {
        return $this->fieldRules();
    }
}
