<?php

namespace App\Containers\Includes\Tasks;

use App\Containers\Includes\Data\Repositories\IncludesRepository;
use App\Ship\Parents\Tasks\Task;

class GetAllIncludesTask extends Task
{

    protected $repository;

    public function __construct(IncludesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run()
    {
        return $this->repository->paginate();
    }
}
