<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends BaseModel
{
    use HasFactory;

    public $guarded = [];

    public $translatedAttributes = [
        'name', 
        'position', 
        'biography_title', 
        'biography_content', 
        'title', 
        'seo_keywords', 
        'seo_description', 
        'description'
    ];

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AboutTranslation::class, 'about_id');
    }

    public function getTranslationRelationKey(): string
    {
        return 'about_id';
    }
}