<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title', 'subtitle', 'subcontent'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function topicCategories()
    {
        return $this->hasMany(TopicCategory::class);
    }
    
    public function getIconUrlAttribute()
    {
        if (!$this->icon) {
            return asset('/default-icon.webp');
        }
        
        return url('storage/'.$this->icon);
    }
}