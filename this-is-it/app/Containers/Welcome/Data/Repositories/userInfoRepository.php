<?php

namespace App\Containers\Welcome\Data\Repositories;

use App\Ship\Parents\Repositories\Repository;

/**
 * Class userInfoRepository
 */
class userInfoRepository extends Repository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

}
