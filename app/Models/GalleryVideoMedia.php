<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryVideoMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'file',
        'status',
        'order',
        'gallery_video_id'
    ];

    public function galleryVideo()
    {
        return $this->belongsTo(GalleryVideo::class, 'gallery_video_id');
    }
}