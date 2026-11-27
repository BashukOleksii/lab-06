<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'bike_rack_id' => $this->bike_rack_id,

            'title' => $this->title,

            'body' => $this->body,

            'created_at' =>
            $this->created_at->toIso8601String(),

            'updated_at' =>
            $this->updated_at->toIso8601String(),
        ];
    }
}
