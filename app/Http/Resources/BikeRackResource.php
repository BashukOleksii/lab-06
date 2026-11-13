<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BikeRackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slot_count' => $this->slot_count,
            'scheduled_opening_on' => $this->scheduled_opening_on->toDateString(),
            'status' => $this->status,
            'district' => new DistrictResource($this->whenLoaded('district')),
        ];
    }
}
