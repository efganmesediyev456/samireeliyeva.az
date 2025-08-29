<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer',
        'order' => 'integer',
        'date' => 'date'
    ];

    public $guarded = [];
    public $translatedAttributes = ['title'];
}