<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
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
            'subtitle' => $this->subtitle,
            'subcontent' => $this->subcontent,
            'icon'=> url('/storage/'.$this->icon),
            'topic_items'=> $this->getGroupedItemsWithTypeNames(),
        ];
    }


    protected function getTypeName($type)
    {
        $types = [
            1 => 'Əmək məcəlləsi',
            2 => 'Təhsil qanunvericiliyi',
        ];

        return $types[$type] ?? ucfirst($type);
    }


    protected function getGroupedItemsWithTypeNames()
    {
        return $this->topicCategories->groupBy('type')->map(function ($items, $type) {

            
            return [
                'type' => $type,
                'type_name' => $this->getTypeName($type),
                'items' => TopicCategoryResource::collection($items),
            ];
        })->values(); 
    }

}