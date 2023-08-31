<?php

namespace App\Containers\Includes\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;

class DeleteIncludesAction extends Action
{
    public function run(Request $request)
    {
        return Apiato::call('Includes@DeleteIncludesTask', [$request->id]);
    }
}
