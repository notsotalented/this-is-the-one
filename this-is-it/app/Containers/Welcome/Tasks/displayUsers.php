<?php

namespace App\Containers\Welcome\Tasks;

use Apiato\Core\Abstracts\Requests\Request;
use App\Ship\Parents\Tasks\Task;
use App\Containers\User\Models\User;

class displayUsers extends Task
{
    public function run(Request $request = NULL)
    {
        //
        return $data = User::all();
    }
}
