<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Video extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'thumbnail',
        'subcategory_id',
        'date'
    ];

    public $translatedAttributes = [
        'title',
        'description'
    ];

    public $casts = [
        'date'=>'datetime'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

}