<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextbookBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image'
    ];

    public function translations()
    {
        return $this->hasMany(TextbookBannerTranslation::class, 'textbook_banner_id');
    }

    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}