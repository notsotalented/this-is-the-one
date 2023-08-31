<?php

namespace App\Containers\Welcome\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\Welcome\UI\WEB\Requests\ListPageRequest;

/**
 * Class listProcessing
 *
 * @package App\Containers\Welcome\UI\WEB\Controllers
 */
class listProcessing extends WebController
{
    public function displayUsers(ListPageRequest $request) {
        //

        $data = Apiato::call('Welcome@displayUsersAction');
          
        return view('welcome::list-page', ['data' => $data[0], 'user' => $data[1]]);
    }
}
