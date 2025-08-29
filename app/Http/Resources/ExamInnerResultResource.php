<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExamQuestionOptionResource;
use App\Http\Resources\ExamQuestionOptionResultResource;

class ExamInnerResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $user = auth("api")->user();
        $userAnswers=$user->userExamAnswers->where('exam_id', $this->exam_id);
        $userSelected = $userAnswers->where('exam_question_id', $this->id)->first();
        
        $checkUserAnswer =  false;

        if($this->type==1){
            $checkUserAnswer = (bool) $userAnswers->where('exam_question_id', $this->id)->first()?->answer_id;
        }else if($this->type==2){
            $checkUserAnswer =  (bool)$userAnswers->where('exam_question_id', $this->id)->first()?->answer;
        }

        return [
            'id' => $this->id,
            'title' => $this->question_text,
            'type' => $this->type,
            "checkUserAnswer"=>$checkUserAnswer,
            "answer"=>$this->when(
                $this->type === 2, 
                $userSelected?->answer
            ),
            'variants' => $this->when(
                $this->type === 1, 
                ExamQuestionOptionResultResource::collection($this->options) 
            ),
        ];
    }
}