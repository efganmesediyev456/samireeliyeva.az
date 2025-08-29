<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryPacketItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'packet_id',
        'icon', 
        'price',
        'discount_price',
    ];

    public $translatedAttributes = [
        'title',
        'subtitle',
        'chooseElement',
        'unChooseElement'
    ];

    
    public function getTranslationRelationKey(): string
    {
        return 'item_id';
    }

    public function packet()
    {
        return $this->belongsTo(SubCategoryPacket::class, 'packet_id');
    }
}