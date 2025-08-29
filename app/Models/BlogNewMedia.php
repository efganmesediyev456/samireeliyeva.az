<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogNewMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_new_media';

    protected $fillable = [
        'file',
        'status',
        'order',
        'blog_new_id'
    ];

    public function blogNew()
    {
        return $this->belongsTo(BlogNew::class, 'blog_new_id');
    }
}