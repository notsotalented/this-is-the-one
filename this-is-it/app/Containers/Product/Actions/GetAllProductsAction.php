<?php

namespace App\Containers\Product\Actions;

use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Requests\Request;
use Apiato\Core\Foundation\Facades\Apiato;

class GetAllProductsAction extends Action
{
    public function run($paginate, $userId = null)
    {
        return Apiato::call('Product@GetAllProductsTask',
                            [$paginate, $userId],
                            [
                                'addRequestCriteria',
                                'ordered'
                            ]
        );
    }
}
