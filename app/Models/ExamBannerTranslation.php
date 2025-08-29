<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBannerTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'exam_banner_id',
        'locale',
        'title',
        'description',
        'seo_keywords',
        'seo_description'
    ];

    public function banner()
    {
        return $this->belongsTo(ExamBanner::class, 'exam_banner_id');
    }
}