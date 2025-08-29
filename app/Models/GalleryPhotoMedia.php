<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryPhotoMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'file',
        'status',
        'order',
        'gallery_photo_id'
    ];

    public function galleryPhoto()
    {
        return $this->belongsTo(GalleryPhoto::class, 'gallery_photo_id');
    }
}