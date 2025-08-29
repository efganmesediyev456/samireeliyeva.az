<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeOnlineLesson extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    
    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $guarded = [];
    public $translatedAttributes = ['title', 'subtitle'];

    public function getIconUrlAttribute()
    {
        if (!$this->icon) {
            return asset('/default.webp');
        }
        
        return url('storage/'.$this->icon);
    }

        public function getTranslationRelationKey(): string
    {
        return 'free_id';
    }
}