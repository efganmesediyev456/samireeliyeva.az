<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasTranslations;

class ExamQuestion extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'position',
        'points',
        'type'
    ];

    public $translatedAttributes = [
        'question_text'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(ExamQuestionOption::class, 'question_id');
    }

    public function getTranslationRelationKey(): string
    {
        return 'question_id';
    }
}