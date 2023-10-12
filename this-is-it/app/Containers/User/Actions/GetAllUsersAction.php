<?php

namespace App\Containers\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Ship\Parents\Actions\Action;

/**
 * Class GetAllUsersAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetAllUsersAction extends Action
{

    /**
     * @return mixed
     */
    public function run($paginate)
    {
        return Apiato::call('User@GetAllUsersTask',
            [$paginate],
            [
                'addRequestCriteria',
                'ordered',
            ]
        );
    }
}
