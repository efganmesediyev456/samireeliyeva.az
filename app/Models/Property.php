<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $translatedAttributes = ['title'];

    /**
     * Get the sub-properties for this property.
     */
    public function subProperties(): HasMany
    {
        return $this->hasMany(SubProperty::class);
    }

    /**
     * Get the products associated with this property.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_property')
            ->withTimestamps();
    }
}
