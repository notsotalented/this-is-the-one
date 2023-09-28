<?php

namespace App\Containers\Product\Actions;

use App\Containers\Product\Models\Product;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Actions\Action;
use Apiato\Core\Foundation\Facades\Apiato;
use Exception;



/*
 *    o  \  私は黒狐です。      0
 *   |\＝| -- -- -- -- >--|> |=
 *  /\ /                    \\
 */

class FindProductByIdAction extends Action
{
  public function run($data)
  {
    try {
      $product = Product::find($data['id']);
    } catch (Exception $e) {
      return false;
    }
  }
}
