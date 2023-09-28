<?php

namespace App\Containers\Product\Actions;

use App\Containers\Product\Models\Product;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;



/*
 *    o \  私は黒狐です。      0
 *   |\＝| -- -- -- -- >--|> |=
 *  /\ /                    \\
 */

class FindProductByIdAction extends Action
{
  public function run(DataTransporter $data)
  {
    try {
      $product = Apiato::call('Product@FindProductByIdTask', [$data->id]);
    } catch (\Exception $e) {
      throw new NotFoundException($e->getMessage());
    }

    return $product;
  }
}
