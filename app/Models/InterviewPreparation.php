<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class InterviewPreparation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'thumbnail',
        'subcategory_id',
        'date',
        'type',
    ];

    public $casts = [
        'date'=>'datetime'
   ];

    public $translatedAttributes = [
        'title'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getTranslationRelationKey(): string
    {
        return 'interview_id';
    }


    public function files(){
        return $this->hasMany(InterviewPreparationFile::class,'interview_preparation_id');
    }

}