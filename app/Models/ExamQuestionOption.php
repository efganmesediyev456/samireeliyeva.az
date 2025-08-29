<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasTranslations;

class ExamQuestionOption extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'is_correct',
        'position'
    ];

    public $translatedAttributes = [
        'option_text'
    ];

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function getTranslationRelationKey(): string
    {
        return 'option_id';
    }
}