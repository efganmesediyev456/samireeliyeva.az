<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Textbook extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'datetime',
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

    public function media()
    {
        return $this->hasMany(TextbookMedia::class, 'textbook_id')->orderBy('order');
    }

    public function keyValues()
    {
        return $this->hasMany(TextBookKeyValue::class, 'text_book_id')->orderBy('order', 'asc');
    }



    // app/Models/Textbook.php içine ekleyin
    public function attributes()
    {
        return $this->hasMany(TextbookAttribute::class);
    }
    
}



