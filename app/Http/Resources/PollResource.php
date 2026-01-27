<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
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
            'options' => $this->options,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status,
            'votes_count' => $this->votes_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_vote' => $this->currentUserVote ? $this->currentUserVote->option : null,
        ];
    }
}
