<?php

namespace App\Containers\Product\Models;

use App\Ship\Parents\Models\Model;
use Apiato\Core\Traits\HashIdTrait;
use Apiato\Core\Traits\HasResourceKeyTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ProductModel extends Model
{
    use HashIdTrait;
    use HasResourceKeyTrait;
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
}
