<?php

namespace App\Containers\Includes\Tasks;

use App\Containers\Includes\Data\Repositories\IncludesRepository;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class FindIncludesByIdTask extends Task
{

    protected $repository;

    public function __construct(IncludesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id)
    {
        try {
            return $this->repository->find($id);
        }
        catch (Exception $exception) {
            throw new NotFoundException();
        }
    }
}
