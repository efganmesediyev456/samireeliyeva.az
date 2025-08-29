<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class LittleVideoRolic extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'thumbnail',
        'subcategory_id',
        'date',
    ];

   public $casts = [
        'date'=>'datetime'
   ];
    public $translatedAttributes = [
        'title',
        'description'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getTranslationRelationKey(): string
    {
        return 'video_id';
    }
}