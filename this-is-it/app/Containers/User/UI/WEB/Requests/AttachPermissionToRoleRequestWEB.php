<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\Authorization\Models\Role;
use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AttachPermissionToRoleRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class AttachPermissionToRoleRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'roles'       => 'admin',
        'permissions' => 'manage-roles',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [
        //'permissions_ids.*',
        //'role_id',
    ];

    /**
     * Defining the URL parameters (`/stores/999/items`) allows applying
     * validation rules on them and allows accessing them like request data.
     *
     * @var  array
     */
    protected $urlParameters = [

    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'permissions_ids'   => '',
            'permissions_ids*' => 'exists:permissions,id',
            'role_id'           => 'required|exists:roles,id',
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        $id = Auth::user()->id;

        $user_role = User::find($id)->roles()->get();

        if ($user_role->first() == null) {
            $user_level = -1;
        }
        else {
            $user_level = 0;
            foreach ($user_role as $item) {
                if ($item->level > $user_level) $user_level = $item->level;
            }
        }

        if($this->role_id)
        {
            $target_role = $this->role_id;
            $target_level = Role::find($target_role)->level;

            if($user_level <= $target_level) return false;
        }


        return $this->check([
            'hasAccess',
        ]);
    }
}
