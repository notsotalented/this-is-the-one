<?php

namespace App\Containers\Includes\Tasks;

use App\Containers\Includes\Data\Repositories\IncludesRepository;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class DeleteIncludesTask extends Task
{

    protected $repository;

    public function __construct(IncludesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id)
    {
        try {
            return $this->repository->delete($id);
        }
        catch (Exception $exception) {
            throw new DeleteResourceFailedException();
        }
    }
}
