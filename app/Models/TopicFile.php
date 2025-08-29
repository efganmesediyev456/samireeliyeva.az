<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopicFile extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'topic_category_id',
        'file_path',
        'original_name',
        'file_type',
        'file_size',
        'status'
    ];

    public $translatedAttributes = ['title'];


    public function topicCategory()
    {
        return $this->belongsTo(TopicCategory::class);
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return url('storage/' . $this->file_path);
    }
}
