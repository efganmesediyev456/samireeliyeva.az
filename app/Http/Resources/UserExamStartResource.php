<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExamStartResource extends JsonResource
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
        $correct = $this->userExams->filter(function ($it) {
            return $it->examQuestionOption?->is_correct  or $it->is_admin_correct==1;
        });
      
        $questionsArrayId = $this->userExams[0]->exam->questions->pluck('id')->toArray();

        $unCorrect = $this->userExams()->whereHas("examQuestionOption", function ($qq) {
                return $qq->where("is_correct", 0);
        })->orWhere(function($e){
            $e->whereHas("examQuestion", function($qq){
                return $qq->where("type",2);
            })->where("is_admin_correct",2);
        })->get();



        $unAnswers = $this->userExams()->where(function($e) {
            return $e->whereHas("examQuestion", function($examQuestion){
                        return $examQuestion->where("type",1);
                    })->whereNull("answer_id");
                })
            ->orWhere(function($e){
                return $e->whereHas("examQuestion", function($examQuestion){
                    return $examQuestion->where("type",2);
                })->whereNull("answer");
            })->get()->pluck("exam_question_id");

        


        $totalEvaluated = count($questionsArrayId);
        $percentage = $totalEvaluated > 0 ? (count($correct) / $totalEvaluated) * 100 : 0;
        $percentage = number_format($percentage);


        return [
            'id' => $this->id,
            'topic' => $this->exam?->title,
            'questionCount' => $this->userExams[0]->exam->questions->count(),
            'questionCorrect' => count($correct),
            'unQuestionCorrect' => count($unCorrect),
            'unAnswers' => count($unAnswers),
            'percentage'=>$percentage.'%',
            'examSlug' => $this->getTranslatedSlugs($this->userExams[0]->exam),
            'active'=>auth('api')->user()->hasActiveSubscription($this->userExams[0]->exam->subCategory)
        ];
    }
}