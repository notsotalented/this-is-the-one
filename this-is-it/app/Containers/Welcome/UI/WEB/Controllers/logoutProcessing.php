<?php

namespace App\Containers\Welcome\UI\WEB\Controllers;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Parents\Controllers\WebController;
use App\Containers\Welcome\Actions\logoutUnsetSessionAction;

/**
 * Class logoutProcessing
 *
 * @package App\Containers\Welcome\UI\WEB\Controllers
 */
class logoutProcessing extends WebController
{
   public function logoutCheck() {
      Apiato::call('Welcome@logoutUnsetSessionAction');

      return redirect(route('home'));
   }
}
