<?php

namespace App\Containers\Product\UI\WEB\Controllers;

use App\Containers\Product\UI\WEB\Requests\AddProductRequest;
use App\Containers\Product\UI\WEB\Requests\GetAllProductsRequest;
use App\Containers\Product\UI\WEB\Requests\ShowAllPersonalProductsRequest;
use App\Containers\Product\UI\WEB\Requests\ShowSpecificProductRequest;
use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Image;

/**
 * Class Controller
 *
 * @package App\Containers\Product\UI\WEB\Controllers
 */
class Controller extends WebController
{
  public function getAllProducts(GetAllProductsRequest $request)
  {
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

    return view('product::product-page', [
      'products' => $products,
    ]);
  }

  public function showAllPersonalProducts(ShowAllPersonalProductsRequest $request)
  {
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

    $selected = [];
    foreach ($products as $product => $value) {
      if (\Auth::user()->id != $value->user_id) {
        $selected[] = $value;
        $products->forget($product);
      }
      ;
    }

    return view('product::product-page', [
      'products' => $products,
    ]);
  }

  public function showSpecificProduct(ShowSpecificProductRequest $request)
  {
    try {
      $product = Apiato::call('Product@FindProductByIdAction', [$request->id]);
    } catch (\Exception $e) {
      return back()->withErrors($e->getMessage());
    }

    return view('product::product-individual-page', [
      'product' => $product
    ]);
  }

  public function showSpecificPersonalProduct(ShowAllPersonalProductsRequest $request)
  {
    try {
      $product = Apiato::call('Product@FindProductByIdAction', [$request->id]);
    } catch (\Exception $e) {
      return back()->withErrors($e->getMessage());
    }

    return view('product::product-individual-page', [
      'product' => $product
    ]);
  }

  public function addProductsPage(ShowAllPersonalProductsRequest $request)
  {
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

    return view('product::product-add-page', [
      'products' => $products,
    ]);
  }

  public function addProductToUser(AddProductRequest $request)
  {
    //Image processing
    $canvasCollection = new Collection;

    if ($request->hasFile('image')) {

      $photos = new Collection;

      if (count($request->image) > 5) {
        return back()->withErrors(['errors' => ['You only can add up to 5 images!']]);
      }

      //Handle the deleted[]
      $deletedString = $request->deleted[0];
      $requestImages = $request->image;

      if ($deletedString) {
        $deletedImages = preg_split("/\,/", $deletedString);

        foreach ($requestImages as $key => $image) {
          if ($deletedImages) {
            if (in_array($image->getClientOriginalName(), $deletedImages)) {
              array_splice($deletedImages, array_search($image->getClientOriginalName(), $deletedImages), 1);
              unset($requestImages[$key]);
            }
          }
        }
      }
    }

    if (count($requestImages) == 0 || !$request->image)
      return back()->withErrors(['errors' => ['You must add at least one image!']]);

    foreach ($request->image as $key => $image) {

      $file = $image;
      $extension = $file->getClientOriginalExtension();
      $filename = Auth()->user()->id . '_' . time() . '_' . $key . '.' . $extension;

      $canvas = Image::canvas(500, 500);
      $image = Image::make($file)->resize(500, 500, function ($constraint) {
        $constraint->aspectRatio();
      });

      $canvas->insert($image, 'center')->encode('png', 100);
      $canvasCollection->push([
        'filename' => $filename,
        'image' => $canvas
      ]);

      $photos->push($filename);
    }

    $result = Apiato::call('Product@AddProductToUserAction', [new DataTransporter($request->all()), $photos]);
    if ($result) {
      foreach ($canvasCollection as $key => $input) {
        $canvas = $input['image'];
        //$canvas->save('uploads/product_images/' . $input['filename']);
        Storage::disk('public')->put('uploads/product_images/' . $filename, $canvas);
      }
    }

    return redirect()->route('web_product_get_all_products')->with('status', 'Product: ' . $result->name . ' added successfully!');
  }
}
