<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasTranslations;

class ExamStatus extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'status',
        'subcategory_id'
    ];

    public $translatedAttributes = [
        'title',
    ];

    public function getTranslationRelationKey(): string
    {
        return 'exam_status_id';
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}