<?php

namespace App\Containers\Product\Tasks;

use App\Containers\Product\Data\Repositories\ProductImagesRepository;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class UploadProductImagesTask extends Task
{

    protected $repository;

    public function __construct(ProductImagesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id, $data): void
    {
        try {
            foreach ($data as $image) {
                $this->repository->create([
                    'name' => $image,
                    'product_id' => $id,
                ]);
            }
        }
        catch (Exception $exception) {
            throw new CreateResourceFailedException();
        }
    }
}
