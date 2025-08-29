<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LangTranslation extends Model
{
    use HasFactory;
    public $casts = [
        'created_at'=>'datetime:Y-m-d H:i:s'
    ];

    public  function getValue($locale)
    {
        return static::where('key', $this->key)
            ->where('filename', $this->filename)
            ->where('locale', $locale)
            ->first()?->value;
    }
}