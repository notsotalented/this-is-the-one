<?php

namespace App\Containers\Includes\Tasks;

use App\Containers\Includes\Data\Repositories\IncludesRepository;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class CreateIncludesTask extends Task
{

    protected $repository;

    public function __construct(IncludesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(array $data)
    {
        try {
            return $this->repository->create($data);
        }
        catch (Exception $exception) {
            throw new CreateResourceFailedException();
        }
    }
}
