<?php

namespace App\Containers\Includes\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;

class GetAllIncludesAction extends Action
{
    public function run(Request $request)
    {
        $includes = Apiato::call('Includes@GetAllIncludesTask', [], ['addRequestCriteria']);

        return $includes;
    }
}
