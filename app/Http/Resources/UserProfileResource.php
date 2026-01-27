<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'full_name' => $this->full_name,
            'phonenumber' => $this->phonenumber,
            'address' => $this->address,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Related data
            'requests' => UserRequestResource::collection($this->whenLoaded('requests')),
            'complaints' => ComplaintResource::collection($this->whenLoaded('complaints')),
            'bills' => AttachBillResource::collection($this->whenLoaded('attachBills')),
            'suggestions' => SuggestionResource::collection($this->whenLoaded('suggestions')),
            'explores' => ExploreResource::collection($this->whenLoaded('explores')),

            // Statistics
            'statistics' => $this->when(isset($this->statistics), function () {
                return $this->statistics;
            }),
        ];
    }
}
