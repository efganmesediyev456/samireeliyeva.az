<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TextBookAttributeResource;

class TextBookResource extends JsonResource
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
            'title'=>$this->title,
            'seo_keywords'=>$data,
            'slug' => $this->getTranslatedSlugs(),
            'seo_description'=>$this->seo_description,
            'description'=>$this->description,
            'image' =>  $this->image_url,
            'media'=>BlogAndNewsMediaResource::collection($this->media->sortByDesc('order')),
            'attributes'=>TextBookAttributeResource::collection($this->attributes->sortByDesc('id'))


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
