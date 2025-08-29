<?php

namespace App\Http\Resources;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogAndNewsResource extends JsonResource
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
            'subtitle' => $this->subtitle,
            'slug' => $this->getTranslatedSlugs(),
            'seo_keywords'=>$data,
            'seo_description'=>$this->seo_description,
            'description'=>$this->description,
            'image' =>  $this->image_url,
            'date' =>  $this->date?->translatedFormat('d F Y', app()->getLocale()),
            'view' =>  $this->view,
            'media'=>BlogAndNewsMediaResource::collection($this->media->sortByDesc('order'))
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


