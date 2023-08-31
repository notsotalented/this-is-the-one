<?php

namespace App\Containers\Includes\Tasks;

use App\Containers\Includes\Data\Repositories\IncludesRepository;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class UpdateIncludesTask extends Task
{

    protected $repository;

    public function __construct(IncludesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id, array $data)
    {
        try {
            return $this->repository->update($data, $id);
        }
        catch (Exception $exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
