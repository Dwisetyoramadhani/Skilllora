<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->event_id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'thumbnail' => Storage::url($this->event_thumbnail),
            'author' => $this->user->name ?? null,
        ];
    }
}
