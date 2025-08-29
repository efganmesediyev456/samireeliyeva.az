<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends BaseModel
{
    use HasFactory;

    public $guarded = [];

    public $translatedAttributes = ['title','seo_keywords','seo_description','description'];


    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany{
        return $this->hasMany(TermsAndConditionTranslation::class, 'terms_id');
    }

    public function getTranslationRelationKey(): string
    {
        return 'terms_id';
    }
    
}
