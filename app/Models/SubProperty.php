<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubProperty extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'property_id'
    ];

    public $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $translatedAttributes = ['title'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the products associated with this sub-property.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sub_property')
            ->withTimestamps();
    }
}
