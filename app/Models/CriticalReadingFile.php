<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriticalReadingFile extends BaseModel
{
    use HasFactory;

    public $guarded = [];

    public $table = 'critical_readings_files';


    public $translatedAttributes = [
        'title'
    ];

    public function getTranslationRelationKey(): string
    {
        return 'file_id';
    }
}