<?php

namespace App\Containers\Product\Tasks;

use App\Containers\Product\Data\Repositories\ProductRepository;
use App\Containers\Product\Models\Product;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class CreateProductTask extends Task
{

    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(string $ownership, string $name, string $description, string $quantity, string $image, string $price, string $brand): Product
    {
        try {
            // create new product
            $product = $this->repository->create([
                'ownership'  => $ownership,
                'name'       => $name,
                'description'=> $description,
                'quantity'   => $quantity,
                'image'      => $image,
                'price'      => $price,
                'brand'      => $brand,
            ]);

        } catch (Exception $e) {
            throw (new CreateResourceFailedException())->debug($e);
        }

        return $product;
    }
}
