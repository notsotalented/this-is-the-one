<?php

namespace App\Containers\Authorization\Actions;

use App\Ship\Parents\Actions\Action;

use Apiato\Core\Foundation\Facades\Apiato;
use Illuminate\Support\Facades\DB;

class GetAllPermissionsAssignedToRoleAction extends Action
{
/**
 * Fetches the role permissions from the database and groups them by permission ID.
 *
 * @return array The grouped role permissions.
 */
    public function run()
    {    
        // Fetch the role permissions from the database
        $collection = DB::table('role_has_permissions')->get();

        // Group the role permissions by permission ID and convert the collection to an array
        return $collection->groupBy('permission_id')->toArray();
    }
}