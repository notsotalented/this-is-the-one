<?php

namespace App\Containers\ReleaseVueJS\Tasks;

use App\Containers\ReleaseVueJS\Data\Repositories\ReleaseVueJSRepository;
use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class CreateReleaseVueJSTask extends Task
{

    protected $repository;

    public function __construct(ReleaseVueJSRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string      $name
     * @param string|null $title_description
     * @param string|null $detail_description
     * @param bool|false $is_publish
     * @param array|null $images
     * @return  mixed
     * @throws  CreateResourceFailedException
     */
    public function run(
        string $name,
        string $title_description = null,
        string $detail_description = null,
        bool $is_publish = false,
        array $images = null
    ): ReleaseVueJS {
        try {
            // create new release
            $release = $this->repository->create([
                'name'               => $name,
                'title_description'  => $title_description,
                'detail_description' => $detail_description,
                'is_publish'         => $is_publish,
                'images'             => $images ?? null,
            ]);

        } catch (Exception $e) {
            throw (new CreateResourceFailedException())->debug($e);
        }
        return $release;
    }
}