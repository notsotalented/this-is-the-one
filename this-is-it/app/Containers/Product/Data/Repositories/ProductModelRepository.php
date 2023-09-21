<?php

namespace App\Containers\Product\Data\Repositories;

use App\Ship\Parents\Repositories\Repository;

/**
 * Class ProductModelRepository
 */
class ProductModelRepository extends Repository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

}
