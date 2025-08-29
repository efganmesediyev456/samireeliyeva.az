<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastExamQuestionItem extends BaseModel
{
    use HasFactory;
    public $translatedAttributes = [
        'title'
    ];

    public function getTranslationRelationKey(): string
    {
        return 'item_id';
    }
}
