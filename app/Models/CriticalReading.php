<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class CriticalReading extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'thumbnail',
        'subcategory_id',
        'date',
        'type',
    ];

    public $casts =[
        'date'=>'datetime:Y-m-d H:i:s'
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
        return 'critical_reading_id';
    }


    public function files(){
        return $this->hasMany(CriticalReadingFile::class, 'critical_reading_id');
    }
}