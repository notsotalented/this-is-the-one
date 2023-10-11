<?php

namespace App\Containers\ReleaseVueJS\Tasks;

use App\Containers\ReleaseVueJS\Data\Repositories\ReleaseVueJSRepository;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateReleaseVueJSTask extends Task
{

    protected $repository;

    public function __construct(ReleaseVueJSRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id, array $data)
    {
        if (empty($data)) {
            throw new UpdateResourceFailedException('Inputs are empty.');
        }
        try {
            return $this->repository->update($data, $id);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundException('Release Not Found.');
        } catch (Exception $exception) {
            throw new UpdateResourceFailedException();
        }
    }
}