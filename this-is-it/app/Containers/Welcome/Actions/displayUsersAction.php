<?php

namespace App\Containers\Welcome\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;
use Illuminate\Support\Facades\DB;

class displayUsersAction extends Action
{
    public function run()
    {
        //Get all from table login_info
        $data_1 = DB::table('products')->get();

        $data_2 = DB::table('users')->get();
        

        return [$data_1, $data_2];
    }
}
