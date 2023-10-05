<?php

namespace App\Containers\Product\Actions;

use App\Containers\Product\Models\Product;
use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;

class AddProductToUserAction extends Action
{
  public function run(DataTransporter $data, $photos): Product
  {

    $product = Apiato::call('Product@CreateProductTask', [
      $data->userId,
      $data->name,
      $data->description,
      $data->quantity,
      $data->price,
      $data->brand
    ]);

    Apiato::call('Product@UploadProductImagesTask', [
      $product->id,
      $photos,
    ]);

    return $product;
  }
}
