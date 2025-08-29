<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExamQuestionOptionResource;

class ExamQuestionOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

      public function orderQuestionAnswer($order)
    {
        $options = $order?->question?->options;

        $sortedOptions = $options?->sortBy('order');

        $result = [];
        if ($sortedOptions and count($sortedOptions)) {
            foreach ($sortedOptions as $index => $option) {

                if ($option->id == $order->id) {
                    $alphabeticValue = chr(65 + $index);
                    return $alphabeticValue;
                }
            }
        }

        return null;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->option_text,
            'variant'=>$this->orderQuestionAnswer($this)
            // 'is_correct'=>$this->is_correct == 1 ? true: false,
        ];
    }
}