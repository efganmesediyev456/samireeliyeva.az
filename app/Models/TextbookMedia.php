<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextbookMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'textbook_media';

    protected $fillable = [
        'file',
        'status',
        'order',
        'textbook_id'
    ];

    public function textbook()
    {
        return $this->belongsTo(Textbook::class, 'textbook_id');
    }
}