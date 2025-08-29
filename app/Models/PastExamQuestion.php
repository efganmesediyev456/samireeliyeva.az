<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastExamQuestion extends BaseModel
{
    use HasFactory;

    public $translatedAttributes = [
        'title',
        'subtitle'
    ];

    
    public function getTranslationRelationKey(): string
    {
        return 'exam_id';
    }


    public function items(){
        return $this->hasMany(PastExamQuestionItem::class, 'past_exam_question_id');
    }
}
