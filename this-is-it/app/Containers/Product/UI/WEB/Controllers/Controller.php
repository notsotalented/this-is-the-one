<?php

namespace App\Containers\Product\UI\WEB\Controllers;

use App\Containers\Product\UI\WEB\Requests\GetAllProductsRequest;
use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;

/**
 * Class Controller
 *
 * @package App\Containers\Product\UI\WEB\Controllers
 */
class Controller extends WebController
{
    public function getAllProducts(GetAllProductsRequest $request) {
        $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

        return view('product::product-page', [
            'products' => $products
        ]);
    }
}
