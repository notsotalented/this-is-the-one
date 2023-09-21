<?php

namespace App\Containers\Product\Models;

use App\Ship\Parents\Models\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';
    protected $fillable = [
        'ownership',
        'name',
        'description',
        'quantity',
        'image',
        'price',
        'brand',
    ];

    protected $attributes = [

    ];

    protected $hidden = [

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
    protected $resourceKey = 'productmodels';
}
