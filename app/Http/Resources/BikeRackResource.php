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

            'district_id' => $this->district_id,

            'slot_count' => $this->slot_count,

            'scheduled_opening_on' =>
            $this->scheduled_opening_on->toDateString(),

            'status' => $this->status,

            'district' => new DistrictResource(
                $this->whenLoaded('district')
            ),

            'confirmation_receipt' => $this->whenLoaded(
                'confirmationReceipt',
                fn() => new ConfirmationReceiptResource(
                    $this->confirmationReceipt
                ),
            ),

            'notes' => NoteResource::collection(
                $this->whenLoaded('notes')
            ),

            'created_at' =>
            $this->created_at->toIso8601String(),

            'updated_at' =>
            $this->updated_at->toIso8601String(),
        ];
    }
}
