<?php

namespace App\Containers\ReleaseVueJS\Tasks;

use App\Containers\ReleaseVueJS\Data\Repositories\ReleaseVueJSRepository;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class DeleteBulkReleaseVueJSTask extends Task
{

    protected $repository;

    /**
     * Constructs a new instance of the class.
     *
     * @param ReleaseVueJSRepository $repository The ReleaseVueJSRepository object.
     */
    public function __construct(ReleaseVueJSRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Deletes the records with the given release IDs.
     *
     * @param array $release_Ids The array of release IDs to delete.
     * @throws DeleteResourceFailedException If the deletion fails.
     * @return int The number of records deleted.
     */
    public function run($release_Ids)
    {
        try {
            return $this->repository->whereIn('id', $release_Ids)->delete();

        } catch (Exception $exception) {
            throw new DeleteResourceFailedException();
        }
    }
}