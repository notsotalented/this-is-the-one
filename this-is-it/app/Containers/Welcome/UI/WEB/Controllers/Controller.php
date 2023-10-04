<?php

namespace App\Containers\Welcome\UI\WEB\Controllers;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\Authorization\Models\Permission;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;

/**
 * Class Controller
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Controller extends WebController
{

  /**
   * @return string
   */
  public function showWelcomePage()
  {
    // No actions to call. Since there's nothing to do but returning a response.

    return view('welcome::welcome-page');
  }

  public function sayWelcome()
  {
    // No actions to call. Since there's nothing to do but returning a response.
    return view('welcome::welcome-page');
  }

  /**
   * Searches the database for users based on the given request.
   *
   * @param Request $request The request object containing the search parameters.
   * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory The response object containing the search results.
   */
  public function search(Request $request)
  {
    if ($request->ajax()) {
      //Hard code search condition 'LIKE' || '='
      $by_id = DB::table('users')->where('id', '=', $request->search_bar)->get();
      $by_name = DB::table('users')->where('name', 'LIKE', '%' . $request->search_bar . '%')->get();
      $by_email = DB::table('users')->where('email', 'LIKE', '%' . $request->search_bar . '%')->get();
      //Products
      $product_by_id = DB::table('products')->where('id', '=', $request->search_bar)->get();
      $product_by_name = DB::table('products')->where('name', 'LIKE', '%' . $request->search_bar . '%')->get();

      $result = new Collection();

      //LIMIT PER CATEGORY
      $offset = 4;

      //BOOL SHOW ALL RESULTS
      $show_all = false;

      if ($by_id) {
        if ($by_id->first() != NULL)
          $result->push('<li><a class="dropdown-item disabled">By ID:</a></li>');

        $current = $result->count();
        foreach ($by_id as $key => $item) {
          $counter = $result->count();
          if ($counter <= $current + $offset) {
            $add = '<li><a class="dropdown-item" href="/users/' . $item->id . '">' . $item->id . '</a></li>';
            $result->push($add);
          }
        }
      }

      if ($by_name) {
        if ($by_id->first() != NULL) {
          $result->push('<li class="dropdown-divider">Name</li>');
        }
        if ($by_name->first() != NULL) {
          $result->push('<a class="dropdown-item disabled">By Name:</a></li>');
        }

        $current = $result->count();
        foreach ($by_name as $key => $item) {
          $counter = $result->count();
          if ($counter <= $current + $offset) {
            $add = '<li><a class="dropdown-item" href="/users/' . $item->id . '">' . htmlspecialchars($item->name) . '</a></li>';
            $result->push($add);
          } else {
            $result->push('<li><a class="dropdown-item disabled">And more...</a></li>');
            $show_all = true;
            break;
          }
        }
      }

      if ($by_email) {
        if ($by_name->first() != NULL) {
          $result->push('<li class="dropdown-divider">Name</li>');
        }
        if ($by_email->first() != NULL) {
          $result->push('<a class="dropdown-item disabled">By Email:</a></li>');
        }

        $current = $result->count();
        foreach ($by_email as $key => $item) {
          $counter = $result->count();
          if ($counter <= $current + $offset) {
            $add = '<li><a class="dropdown-item" href="/users/' . $item->id . '">' . htmlspecialchars($item->email) . '</a></li>';
            $result->push($add);
          } else {
            $result->push('<li><a class="dropdown-item disabled">And more...</a></li>');
            $show_all = true;
            break;
          }
        }
      }

      if ($product_by_id) {
        if ($by_id->first() != NULL || $by_email != NULL) {
          $result->push('<li class="dropdown-divider"></li>');
        }
        if ($product_by_id->first() != NULL) {
          $result->push('<a class="dropdown-item disabled">Product ID:</a></li>');
        }

        $current = $result->count();
        foreach ($product_by_id as $key => $item) {
          $counter = $result->count();
          if ($counter <= $current + $offset) {
            $add = '<li><a class="dropdown-item" href="/products/' . $item->id . '">' . $item->id . '</a></li>';
            $result->push($add);
          } else {
            $result->push('<li><a class="dropdown-item disabled">And more...</a></li>');
            $show_all = true;
            break;
          }
        }
      }

      if ($product_by_name) {
        if ($by_id->first() != NULL || $by_email != NULL) {
          $result->push('<li class="dropdown-divider"></li>');
        }
        if ($product_by_name->first() != NULL) {
          $result->push('<a class="dropdown-item disabled">Product Name:</a></li>');
        }

        $current = $result->count();
        foreach ($product_by_name as $key => $item) {
          $counter = $result->count();
          if ($counter <= $current + $offset) {
            $add = '<li><a class="dropdown-item" href="/products/' . $item->id . '">' . htmlspecialchars($item->name) . '</a></li>';
            $result->push($add);
          } else {
            $result->push('<li><a class="dropdown-item disabled">And more...</a></li>');
            $show_all = true;
            break;
          }
        }
      }

      if ($show_all) {
        $add = '<li><a class="dropdown-item active" style="font-weight: bold; font-style: italic" href="/users?search=' . $request->search_bar . '">Show all results!</a></li>';
        $result->push($add);
      }


      if ($result->first() != NULL) {
        return response($result);
      } else {
        $result = new Collection;
        $result->push('<li><a class="dropdown-item disabled">Not found anything!</a></li>');
        return response($result);
      }
    } else {
      $result = new Collection;
      $result->push('<li><a class="dropdown-item disabled">Not Ajax!</a></li>');
      return response($result);
    }
  }

  public function showTest($id = NULL, $uri = NULL)
  {
    //TEST GROUND
    //TEST GROUND

    return view('welcome::test-page');
  }
}
