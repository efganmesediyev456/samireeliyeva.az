<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InterviewPreparationResource extends JsonResource
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
            'video_type' => $this->when($this->type == 1,\Str::startsWith($this->video_url, 'video_uploads') ? 'local' :'iframe'),
            'video_url'=>$this->when($this->type == 1,\Str::startsWith($this->video_url, 'video_uploads') ? asset('storage/'.$this->video_url) : $this->video_url),
            
            'thumbnail' => $this->when($this->type == 1, url('storage/' . $this->thumbnail)),
            'date' => $this->when($this->type == 1, $this->date?->translatedFormat('d F Y', app()->getLocale())),
            'description'=>$this->when($this->type == 1 && $this->description, $this->description),
            'files' => $this->when($this->type == 2, function () {
                return $this->files->sortByDesc('id')->values()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'file_url' => url('storage/' . $item->file_url),
                        'title'=>$item->title
                    ];
                });
            }),
        ];
    }

}