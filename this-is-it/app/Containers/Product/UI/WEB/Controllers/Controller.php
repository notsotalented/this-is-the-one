<?php

namespace App\Containers\Product\UI\WEB\Controllers;

use App\Containers\Product\UI\WEB\Requests\AddProductRequest;
use App\Containers\Product\UI\WEB\Requests\GetAllProductsRequest;
use App\Containers\Product\UI\WEB\Requests\ShowAllPersonalProductsRequest;
use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Collection;
use Image;

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

        $selected = [];
        foreach ($products as $product => $value) {
            if(\Auth::user()->id != $value->ownership) {
                $selected[] = $value;
                $products->forget($product);
            };
        }

        return view('product::product-page', [
            'products' => $products,
        ]);
    }

    public function addProductsPage(ShowAllPersonalProductsRequest $request) {       
        $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

        return view('product::product-add-page', [
            'products' => $products,
        ]);
    }

    public function addProductToUser(AddProductRequest $request) {
        //Image processing
        $canvasCollection = new Collection;

        if ($request->hasFile('image')) {            
            
            $photos = new Collection;
            foreach ($request->image as $key => $image) {
                if($key >= 4) break;

                $file = $image;
                $extension = $file->getClientOriginalExtension();
                $filename =  Auth()->user()->id . '_' . time() . '_' . $key . '.' . $extension;

                $canvas = Image::canvas(500, 500);
                $image  = Image::make($file)->resize(500, 500, function($constraint) {
                    $constraint->aspectRatio();
                });

                $canvas->insert($image, 'center');
                //$canvas->save('uploads/product_images/' . $filename);
                $canvasCollection->push([
                    'filename' => $filename,
                    'image' => $canvas
                ]);

                $photos->push($filename);
            }
        }
        else {
            return back()->with('status', 'Images not found!');
        }
        
        $result = Apiato::call('Product@AddProductToUserAction', [new DataTransporter($request->all()), $photos]);
        if($result) {
            foreach ($canvasCollection as $key => $input) {
                $canvas = $input['image'];
                $canvas->save('uploads/product_images/' . $input['filename']);
            }
        }

        return redirect()->route('web_product_get_all_products')->with('status', 'Product: ' . $result->name . ' added successfully!');
    }
}
