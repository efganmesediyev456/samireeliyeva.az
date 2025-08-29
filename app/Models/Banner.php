<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['image', 'status', 'order'];
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer',
        'order' => 'integer'
    ];

    public $translatedAttributes = ['title'];
}