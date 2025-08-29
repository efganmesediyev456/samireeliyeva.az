<?php

namespace App\Http\Resources;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = json_decode($this->seo_keywords, true);
        $data = [];
        if(is_array($array) and count($array)){
           foreach ($array as $key => $value) {
               $value['id']=$key+1;
               $data[] = $value;
           }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->getTranslatedSlugs(),
            'seo_keywords'=>$data,
            'seo_description'=>$this->seo_description,
            'description'=>$this->description,
            'image' =>  $this->image_url,
            'date' =>  $this->created_at?->translatedFormat('d F Y', app()->getLocale()),
            'media_count'=> $this->media->count(),
            'media'=>BlogAndNewsMediaResource::collection(resource: $this->media->sortByDesc('order'))
        ];
    }

    private function getTranslatedSlugs(): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $this->translate($lang)->slug ?? null;
        }

        return $slugs;
    }
}


