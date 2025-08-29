<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Language;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamBannerResource extends JsonResource
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
        $subscriptionCount = Order::where('status','completed')->where('expires_at','>', now())?->pluck('user_id')?->unique()?->count();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'exam_online_title' => $this->exam_online_title,
            'description' => $this->description,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $data,
            'image' => url('/storage/'.$this->image),
            'exam_online_image' => url('/storage/'.$this->exam_online_image),
            'categoryCount'=>Category::count(),
            'subscriptionsCount'=>$subscriptionCount
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
