<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachBillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'citizen_id' => (string) $this->citizen_id,
            'title' => $this->title,
            'desc' => $this->desc,
            'amount' => $this->amount,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'paid_date' => $this->paid_date?->format('Y-m-d'),
            'paid' => (int) !is_null($this->paid_date),
            'note' => $this->note,
            'citizen' => [
                'id' => (string) $this->citizen_id,
                'name' => optional($this->whenLoaded('citizen', $this->citizen))->full_name,
                'phone' => optional($this->whenLoaded('citizen', $this->citizen))->phonenumber,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
