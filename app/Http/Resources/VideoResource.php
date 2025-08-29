<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video_type' => \Str::startsWith($this->video_url, 'video_uploads') ? 'local' :'iframe',
            'video_url'=>\Str::startsWith($this->video_url, 'video_uploads') ? asset('storage/'.$this->video_url) : $this->video_url,
            'thumbnail'=>url('storage/'.$this->thumbnail),
            'date'=>$this->date?->translatedFormat('d F Y', app()->getLocale()),
        ];
    }
}