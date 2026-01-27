<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'id' => $this->id,
                'citizen_id' => $this->citizen_id,
                'citizen' => [
                    'id' => $this->citizen->id,
                    'full_name' => $this->citizen->full_name,
                    'phonenumber' => $this->citizen->phonenumber,
                ],
                'desc' => $this->desc,
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ];
    }
}
