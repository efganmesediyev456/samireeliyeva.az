<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalRegion extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('/default.webp');
        }
        
        return url('storage/'.$this->image);
    }

    public function getTranslationRelationKey(): string
    {
        return 'region_id';
    }
}