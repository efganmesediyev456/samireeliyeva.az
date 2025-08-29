<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryPacket extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'duration_months', 
        'active',
    ];

    public $translatedAttributes = [
        'title',
    ];

    
    public function getTranslationRelationKey(): string
    {
        return 'packet_id';
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function items()
    {
        return $this->hasMany(SubCategoryPacketItem::class, 'packet_id');
    }
}