<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextBookKeyValueTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'key',
        'value'
    ];
}