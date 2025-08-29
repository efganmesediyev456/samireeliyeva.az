<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SubScriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    private function getTranslatedSlugs($item): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $item->translate($lang)->slug ?? null;
        }

        return $slugs;
    }


    public function toArray($request)
    {
        $now = Carbon::now();
        $expiryDate = Carbon::parse($this?->expires_at);
        $status = $now->lt($expiryDate);

        $text = '';

        if ($status) {
            $text = $this->packageItem?->title . ' paketinə ' . $this->duration_months . ' aylıq abunə olundu';
        } else {
            $text = $this->packageItem?->title . ' ' . $this->duration_months . ' aylıq paketinizin istifadə müddəti bitdi';
        }

        return [
            'id' => $this->id,
            'package_name' => $this->packageItem?->title,
            'package_icon' => url('storage/' . $this->packageItem?->icon),
            'date' => $this->updated_at->translatedFormat("d F Y", app()->getLocale()) . '-' . $this->expires_at?->translatedFormat("d F Y", app()->getLocale()),
            'order_text' => $text,
            'subCategoryName' => $this->subCategory?->title,
            'active' => $status,
            'slug'=>$this->getTranslatedSlugs($this->subcategory)
        ];
    }
}