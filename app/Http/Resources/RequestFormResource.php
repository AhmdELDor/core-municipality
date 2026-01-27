<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestFormResource extends JsonResource
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
            'description' => $this->description,
            'fields' => $this->fields,
            'version' => $this->version,
            'status' => $this->status,
            'instructions' => $this->instructions,
            'attachments_required' => $this->attachments_required,
            'fee_amount' => $this->fee_amount,
            'allowed_file_types' => $this->allowed_file_types,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
