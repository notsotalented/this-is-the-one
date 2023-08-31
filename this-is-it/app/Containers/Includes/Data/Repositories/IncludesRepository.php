<?php

namespace App\Containers\Includes\Data\Repositories;

use App\Ship\Parents\Repositories\Repository;

/**
 * Class IncludesRepository
 */
class IncludesRepository extends Repository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

}
