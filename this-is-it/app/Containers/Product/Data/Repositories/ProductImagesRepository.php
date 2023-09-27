<?php

namespace App\Containers\Product\Data\Repositories;

use App\Ship\Parents\Repositories\Repository;

/**
 * Class ProductImagesRepository
 */
class ProductImagesRepository extends Repository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

}
