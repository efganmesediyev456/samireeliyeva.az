<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextbookBanner extends BaseModel
{
    use HasFactory;

    public $guarded = [];

    public $translatedAttributes = [
        'title', 
        'seo_keywords', 
        'seo_description', 
        'description'
    ];

    // public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(AboutTranslation::class, 'about_id');
    // }

    // public function getTranslationRelationKey(): string
    // {
    //     return 'about_id';
    // }
}