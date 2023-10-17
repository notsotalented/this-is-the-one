<?php

namespace App\Containers\ReleaseVueJS\Tasks;

use App\Containers\ReleaseVueJS\Data\Repositories\ReleaseVueJSRepository;
use App\Ship\Criterias\Eloquent\OrderByCreationDateDescendingCriteria;
use App\Ship\Parents\Tasks\Task;

class GetAllReleaseVueJsTask extends Task
{

    protected $repository;

    public function __construct(ReleaseVueJSRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($paginate = 10)
    {
        return $this->repository->paginate($paginate);
    }
    public function ordered()
    {
        $this->repository->pushCriteria(new OrderByCreationDateDescendingCriteria());
    }
}
