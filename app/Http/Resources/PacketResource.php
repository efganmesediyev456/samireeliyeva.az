<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PacketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     public function tagChangeValue($item, $key){
         $array = json_decode($item->$key, true);
        $data = [];
        if(is_array($array) and count($array)){
           foreach ($array as $key => $value) {
               $value['id']=$key+1;
               $data[] = $value;
           }
        }
        return $data;
     }

    public function toArray($request)
    {

        

        return [
            'id' => $this->id,
            'title' => $this->title,
            'items'=>$this->items?->map(fn($item)=>[
                 'id'=>$item->id,
                 'duration_months' => $this->duration_months,
                'title'=>$item->title,
                'subtitle'=>$item->subtitle,
                'chooseElements'=>$this->tagChangeValue($item, 'chooseElement'),
                'unChooseElements'=>$this->tagChangeValue($item, 'unChooseElement'),
                'icon'=>url('storage/'.$item->icon),
                'price'=>$item->price,
                'discountPrice'=>$item->discount_price,

            ])
        ];
    }
}