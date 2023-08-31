<?php

namespace App\Containers\Includes\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;

class CreateIncludesAction extends Action
{
    public function run(Request $request)
    {
        $data = $request->sanitizeInput([
            // add your request data here
        ]);

        $includes = Apiato::call('Includes@CreateIncludesTask', [$data]);

        return $includes;
    }
}
