<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\Authorization\Models\Role;
use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdateUserRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class PowerUpdateUserRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'permissions' => 'manage-roles', 'update-users', 'search-users',
        'roles'       => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [
        //'id',
    ];

    /**
     * Defining the URL parameters (`/stores/999/items`) allows applying
     * validation rules on them and allows accessing them like request data.
     *
     * @var  array
     */
    protected $urlParameters = [
        //'id',
    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'email'    => 'nullable|email|unique:users,email',
            'id'       => 'required|exists:users,id',
            'password_new' => 'nullable|min:5|max:40',
            'name'     => 'min:0|max:50',
            'roles_id' => '',
            'roles_ids*' => 'exists:roles,id'
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        // is this an admin who has access to permission `update-users`
        // or the user is updating his own object (is the owner).

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

        $target_role = User::find($this->id)->roles()->get();

        if($target_role->first() == null) {
            $target_level = -1;
        }
        else {
            $target_level = 0;
            foreach ($target_role as $item) {
                if ($item->level > $target_level) $target_level = $item->level;
            }
        }

        $isOwner = $this->check(['isOwner']);

        if($user_level <= $target_level && $isOwner == false) return false;

        $roles_ids = $this->roles_ids;
        if($roles_ids != null) 
            {        
                $role_valid = true;
                foreach ($roles_ids as $item) {
                    $role_level = Role::findById($item)->level;
                    if ($user_level < $role_level) {
                        $role_valid = false;
                        break;
                    }
                }
                if(!$role_valid) return false;
            }

        return $this->check([
            'hasAccess|isOwner',
        ]);
    }
}
