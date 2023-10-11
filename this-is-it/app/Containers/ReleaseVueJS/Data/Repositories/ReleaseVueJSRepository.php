<?php

namespace App\Containers\ReleaseVueJS\Data\Repositories;

use App\Ship\Parents\Repositories\Repository;

/**
 * Class ReleaseVueJSRepository
 */
class ReleaseVueJSRepository extends Repository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id'                 => '=',
        'name'               => 'like',
        'title_description'  => 'like',
        'detail_description' => 'like',
        'is_publish',
        'images',
        'created_at'         => 'like',
    ];

}