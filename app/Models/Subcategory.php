<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends BaseModel
{
   
    public $table ='subcategories';
    use SoftDeletes;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title','seo_keywords','seo_description', 'slug','description','subtitle'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('/default.webp');
        }
        
        return url('storage/'.$this->image);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }


    public function videos(){
        return $this->hasMany(Video::class);
    }

    public function littleVideoRolics(){
        return $this->hasMany(LittleVideoRolic::class);
    }

    public function tests(){
        return $this->hasMany(Exam::class);
    }

    public function interviewPreparations(){
        return $this->hasMany(InterviewPreparation::class);
    }

    public function essayExamples(){
        return $this->hasMany(EssayExample::class);
    }


    public function criticalReadings(){
        return $this->hasMany(CriticalReading::class);
    }

     
    public function packets(){
        return $this->hasMany(SubCategoryPacket::class, 'subcategory_id');
    }


    
}