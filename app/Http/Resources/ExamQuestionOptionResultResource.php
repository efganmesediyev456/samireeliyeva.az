<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ExamQuestionOptionResource;

class ExamQuestionOptionResultResource extends JsonResource
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
        $userAnswers=$user->userExamAnswers->where('exam_id', $this->question?->exam_id);
        $userSelected = $userAnswers->where('exam_question_id', $this->question->id)->first();

        return [
            'id' => $this->id,
            'title' => $this->option_text,
            'isCorrect'=>$this->is_correct == 1 ? true: false,
            // 'userCorrect'=>$userSelected?->answer_id == $this->id and $this->is_correct,
            'userSelected'=>$userAnswers->pluck("answer_id")->contains($this->id)
        ];
    }
}