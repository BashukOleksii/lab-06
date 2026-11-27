<?php

namespace App\Http\Requests;

use App\Models\BikeRack;
use App\Queries\BikeRackListQuery;
use Illuminate\Foundation\Http\Attributes\RedirectToRoute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

#[RedirectToRoute('bike-racks.index')]
class IndexBikeRackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(
            'viewAny',
            BikeRack::class
        ) ?? false;
    }

    public function validationData(): array
    {
        return $this->query();
    }

    public function rules(): array
    {
        return [
            'district_id' => [
                'bail',
                'nullable',
                'integer',
                'exists:districts,id',
            ],

            'status' => [
                'nullable',
                'string',
                Rule::in(['draft', 'confirmed']),
            ],

            'search' => [
                'nullable',
                'string',
                'max:120',
            ],

            'sort' => [
                'nullable',
                'string',
                Rule::in(array_keys(BikeRackListQuery::SORTS)),
            ],

            'per_page' => [
                'nullable',
                'integer',
                'between:1,50',
            ],

            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }
}
