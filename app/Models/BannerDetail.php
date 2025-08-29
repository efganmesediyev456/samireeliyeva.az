<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerDetail extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['icon', 'status', 'order'];
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'status' => 'integer',
        'order' => 'integer'
    ];

    public $translatedAttributes = ['title', 'subtitle'];
}