<?php

namespace App\Containers\Welcome\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\Welcome\Data\Transporters\loginProcessingTransporter;
use App\Containers\Welcome\Exceptions\userNotMatch;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

/**
 * Class deleteProcessing
 *
 * @package App\Containers\Welcome\UI\WEB\Controllers
 */
class handleDU extends WebController
{
    /*
    public function duCheck(Request $request) 
    {
        if ($request->username != session('login')) {
            throw new userNotMatch;
        }

        //Run login to check the password, preferably turn login into a Task
        Apiato::call('Welcome@checkLoginAction', [$data = new loginProcessingTransporter($request->toArray())]);

        $message = Apiato::call('Welcome@checkDUAction', [$data = new loginProcessingTransporter($request->toArray())]);

        return redirect(route('list-page'))->with(['targetuser'=> $request->target, 'message' => $message]);
    }
    */
}
