<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $casts = [
        "created_at"=>"datetime:Y-m-d H:i:s",
        "expires_at"=>"datetime:Y-m-d H:i:s",
    ];

    public $guarded = [];


    public function user(){
        return $this->belongsTo(User::class);
    }


    public function subCategory(){
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }


    public function packageItem(){
        return $this->belongsTo(SubCategoryPacketItem::class,'package_item_id');
    }
   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrderNumber = self::max('order_number');
            $order->order_number = $lastOrderNumber ? ((int)$lastOrderNumber + 1) : 1000000;
        });
    }

}
