<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextbookBannerTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'textbook_banner_id',
        'locale',
        'title',
        'description',
        'seo_keywords',
        'seo_description'
    ];

    public function banner()
    {
        return $this->belongsTo(TextbookBanner::class, 'textbook_banner_id');
    }
}