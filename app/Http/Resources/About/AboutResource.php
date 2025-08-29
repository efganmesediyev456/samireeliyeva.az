<?php

namespace App\Http\Resources\About;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
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
            'name' => $this->name,
            'position' => $this->position,
            'biography_title' => $this->biography_title,
            'title' => $this->title,
            'biography_content' => $this->biography_content,
            'description' => $this->description,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $data,
            'image' => url('/storage/'.$this->image),
            'pdf' => url('/storage/'.$this->pdf),
            'published_books_count' => $this->published_books_count,
            'certificates_count' => $this->certificates_count,
            'years_in_profession' => $this->years_in_profession,
        ];
    }



    private function getTranslatedSlugs(): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $this->getTranslation('slug', $lang) ?? null;
        }

        return $slugs;
    }

}
