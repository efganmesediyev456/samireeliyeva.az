<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextbookAttribute extends BaseModel
{
    protected $fillable = ['key', 'value', 'textbook_id', 'language_code'];

    public function textbook()
    {
        return $this->belongsTo(Textbook::class);
    }

    public $translatedAttributes = ['key','value'];


    public function getTranslationRelationKey(): string
    {
        return 'attr_id';
    }
}