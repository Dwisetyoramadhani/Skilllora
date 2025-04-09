<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->course_id,
            'title' => $this->course_title,
            'description' => $this->description,
            'category' => $this->category->category_title ?? null,
            'thumbnail' => Storage::url($this->thumbnail),
            'author' => $this->user->name ?? null,
            'vidio_link' => $this->vidio_link,
            'videos' => $this->videos->map(function ($video) {
                return [
                    'id' => $video->id,
                    'part_number' => $video->part_number,
                    'title' => $video->title,
                    'video_url' => Storage::url($video->video_path),
                ];
            }),
        ];
    }
}
