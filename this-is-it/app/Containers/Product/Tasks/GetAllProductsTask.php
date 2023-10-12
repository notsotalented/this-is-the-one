<?php

namespace App\Containers\Product\Tasks;

use App\Containers\Product\Data\Repositories\ProductRepository;
use App\Ship\Criterias\Eloquent\OrderByFieldCriteria;
use App\Ship\Parents\Tasks\Task;

class GetAllProductsTask extends Task
{

    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($paginate, $userId)
    {
        if($userId) return $this->repository->where('user_id', $userId)->paginate($paginate);

        return $this->repository->paginate($paginate);
    }

    public function ordered() {
        $this->repository->pushCriteria(new OrderByFieldCriteria('id', 'asc'));
    }
}
