<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'title' => $this->title,
            'desc' => $this->desc,
            'user' => [
                'full_name' => $this->user->full_name,
                'phonenumber' => $this->user->phonenumber,
            ],
            'images_url' => $this->images_url,
            'status' => $this->status,
            'result' => $this->result,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
