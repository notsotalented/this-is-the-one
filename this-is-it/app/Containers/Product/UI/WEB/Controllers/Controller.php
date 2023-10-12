<?php

namespace App\Containers\Product\UI\WEB\Controllers;

use App\Containers\Product\UI\WEB\Requests\AddProductRequest;
use App\Containers\Product\UI\WEB\Requests\GetAllProductsRequest;
use App\Containers\Product\UI\WEB\Requests\ProductAddPageAccessRequest;
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
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate ?? 8]);

    return view('product::product-page', [
      'products' => $products,
    ]);
  }

  public function showAllPersonalProducts(ShowAllPersonalProductsRequest $request)
  {
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

    $selected = [];
    foreach ($products as $product => $value) {
      if ($request->userId != $value->user_id) {
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

  /**
   * Show a specific personal product.
   *
   * @param ShowAllPersonalProductsRequest $request the request object
   * @throws \Exception if an error occurs
   * @return \Illuminate\View\View | \Illuminate\Http\RedirectResponse the redirect response
   */
  public function showSpecificPersonalProduct(ShowAllPersonalProductsRequest $request)
  {
    try {
      $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter(['id' => $request->userId])]);
      $product = Apiato::call('Product@FindProductByIdAction', [$request->id]);
    } catch (\Exception $e) {
      return back()->withErrors($e->getMessage());
    }

    if ($user->id != $product->user_id) {
      return back()->withErrors(['errors' => ['Product not found!']]);
    }

    return view('product::product-individual-page', compact('product'));
  }

  public function addProductsPage(ProductAddPageAccessRequest $request)
  {
    $products = Apiato::call('Product@GetAllProductsAction', [$request->paginate]);

    return view('product::product-add-page', [
      'products' => $products,
    ]);
  }

  /**
   * Adds a product to a user.
   *
   * @param AddProductRequest $request The request object containing the product details.
   * @return \Illuminate\Http\RedirectResponse The redirect response after adding the product.
   */
  public function addProductToUser(AddProductRequest $request)
  {
    // Initialize the canvas collection
    $canvasCollection = new Collection;

    // Check if the request contains an image file
    if ($request->hasFile('image')) {

      // Initialize the photos collection
      $photos = new Collection;

      // Check if the number of images exceeds the limit
      if (count($request->image) > 5) {
        return back()->withErrors(['errors' => ['You can only add up to 5 images!']]);
      }

      // Handle the deleted[] parameter
      $deletedString = $request->deleted[0];
      $requestImages = $request->image;

      // Remove deleted images from the request images
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

    // Check if no images are present in the request
    if (count($requestImages) == 0 || !$request->image) {
      return back()->withErrors(['errors' => ['You must add at least one image!']]);
    }

    // Process each image in the request
    foreach ($request->image as $key => $image) {

      // Get the file details
      $file = $image;
      $extension = $file->getClientOriginalExtension();
      $filename = Auth()->user()->id . '_' . time() . '_' . $key . '.' . $extension;

      $canvas = Apiato::call('Product@ImageProcessingTask', [$file]);

      // Add the filename and canvas to the canvas collection
      $canvasCollection->push([
        'filename' => $filename,
        'image' => $canvas
      ]);

      // Add the filename to the photos collection
      $photos->push($filename);
    }

    // Call the AddProductToUserAction and pass the data transporter and photos
    $result = Apiato::call('Product@AddProductToUserAction', [new DataTransporter($request->all()), $photos]);

    // If the product is added successfully, store the images
    if ($result) {
      foreach ($canvasCollection as $key => $input) {
        $canvas = $input['image'];
        Storage::disk('public')->put('uploads/product_images/' . $input['filename'], $canvas);
      }
    }

    // Redirect to the all products page with a success message
    return redirect()->route('web_product_get_all_products')->with('status', 'Product: ' . $result->name . ' added successfully!');
  }
}
