<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

class TextBookKeyValue extends BaseModel
{
    use Translatable;

    protected $table = 'text_book_key_values';

    protected $fillable = [
        'text_book_id',
        'order'
    ];

    public $translatedAttributes = [
        'key', 
        'value'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    /**
     * Get the textbook that owns this key-value pair
     */
    public function textbook()
    {
        return $this->belongsTo(Textbook::class, 'text_book_id');
    }

    public function getTranslationRelationKey(): string
    {
        return 'key_value_id';
    }

    
}