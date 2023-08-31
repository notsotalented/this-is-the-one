<?php

namespace App\Containers\Includes\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;

class UpdateIncludesAction extends Action
{
    public function run(Request $request)
    {
        $data = $request->sanitizeInput([
            // add your request data here
        ]);

        $includes = Apiato::call('Includes@UpdateIncludesTask', [$request->id, $data]);

        return $includes;
    }
}
