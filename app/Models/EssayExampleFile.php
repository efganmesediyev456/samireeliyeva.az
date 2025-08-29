<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExampleFile extends BaseModel
{
    use HasFactory;
   
    public $guarded = [];

    public $table = 'essay_examples_files';

    public $translatedAttributes = [
        'title'
    ];

    public function getTranslationRelationKey(): string
    {
        return 'file_id';
    }
}