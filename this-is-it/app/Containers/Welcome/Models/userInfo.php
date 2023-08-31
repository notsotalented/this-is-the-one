<?php

namespace App\Containers\Welcome\Models;

use App\Ship\Parents\Models\Model;

class userInfo extends Model
{
    protected $fillable = [
        'ID',
        'username',
        'password',
        'role',
        'created_at',
    ];

    protected $attributes = [

    ];

    protected $hidden = [
        'password',
    ];

    protected $table = 'login_info';

    protected $casts = [
        'username' => 'string',
        'role' => 'int',
        'password' => 'string',
    ];

    protected $dates = [
        'created_at',
    ];

    /**
     * A resource key to be used by the the JSON API Serializer responses.
     */
    protected $resourceKey = 'userinfos';
}
