<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewPreparationFile extends BaseModel
{

    public $guarded = [];

    public $table = 'interview_preparations_files';
    public $translatedAttributes = [
        'title'
    ];
    public function getTranslationRelationKey(): string
    {
        return 'file_id';
    }
}
