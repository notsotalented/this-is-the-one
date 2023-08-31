<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\Authorization\Models\Role;
use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class FindRoleRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteRoleRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'roles'       => '',
        'permissions' => 'manage-roles',
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
            'id' => 'required|exists:roles,id'
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

        $target_level = Role::findById($this->id)->level;

        if($user_level <= $target_level) return false;

        return $this->check([
            'hasAccess',
        ]);
    }
}
