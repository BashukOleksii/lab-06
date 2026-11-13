<?php

namespace App\Http\Requests;

use App\Models\BikeRack;

class UpdateBikeRackRequest extends BikeRackRequest
{
    public function authorize(): bool
    {
        $bikeRack = $this->route('bike_rack');

        return $bikeRack instanceof BikeRack
            && ($this->user()?->can('update', $bikeRack) ?? false);
    }

    public function rules(): array
    {
        return array_map(
            static fn(array $rules): array => ['sometimes', ...$rules],
            $this->fieldRules(),
        );
    }
}
