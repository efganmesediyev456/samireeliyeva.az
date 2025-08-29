<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBanner extends BaseModel
{
    use HasFactory;

    public $guarded = [];

    public $translatedAttributes = [
        'title', 
        'seo_keywords', 
        'seo_description', 
        'description',
        'exam_online_title'
    ];

    // public function getTranslationRelationKey(): string
    // {
    //     return 'about_id';
    // }
}