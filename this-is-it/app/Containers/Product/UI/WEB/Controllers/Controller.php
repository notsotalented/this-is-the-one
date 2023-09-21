<?php

namespace App\Containers\Product\UI\WEB\Controllers;

use App\Containers\Product\UI\WEB\Requests\AddProductRequest;
use App\Containers\Product\UI\WEB\Requests\GetAllProductsRequest;
use App\Containers\Product\UI\WEB\Requests\ShowAllPersonalProductsRequest;
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

        return view('product::product-page',[
            'products' => $products,
        ]);
    }

    public function showAllPersonalProducts(ShowAllPersonalProductsRequest $request) {
        $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

        return view('product::product-page', [
            'products' => $products,
        ])->with('status', 'Wazz');
    }

    public function addProductsPage(ShowAllPersonalProductsRequest $request) {       
        $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

        return view('product::product-add-page', [
            'products' => $products,
        ])->with('status', 'Wazz');
    }

    public function addProductToUser(AddProductRequest $request) {
        dd($request);
        
        return null;
    }
}
