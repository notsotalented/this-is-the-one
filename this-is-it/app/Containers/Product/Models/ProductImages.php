<?php

namespace App\Containers\Product\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImages extends Model
{
    protected $fillable = [
        'product_id',
        'name'
    ];

    protected $casts = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * A resource key to be used by the the JSON API Serializer responses.
     */
    protected $resourceKey = 'productImages';

    public function getOwner(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
