<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\Authorization\Models\Role;
use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterUserRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class RegisterPowerUserRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'permissions' => 'manage-roles',
        'roles'       => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [

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
            'email'    => 'required|email|max:40|unique:users,email',
            'password' => 'required|min:5|max:30',
            'name'     => 'min:2|max:50',
            'gender'   => '',
            'birth' => 'date',
            'roles_ids' => '',
            'roles_ids*' => 'exists:roles,id'
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

        $roles_ids = $this->roles_ids;
        if($roles_ids != null) 
            {        
                $role_valid = true;
                foreach ($roles_ids as $item) {
                    $role_level = Role::findById($item)->level;

                    if ($user_level <= $role_level) {
                        $role_valid = false;
                        break;
                    }
                }
                if(!$role_valid) return false;
            }
        return $this->check([
            'hasAccess',
        ]);
    }
}
