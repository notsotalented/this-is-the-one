<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CreateRoleRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CreateRoleRequestWEB extends Request
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
            'name'         => 'required|unique:roles,name|min:2|max:20|no_spaces',
            'description'  => 'max:255',
            'display_name' => 'max:100',
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

        $target_level = $this->level;

        if($user_level <= $target_level) return false;

        return $this->check([
            'hasAccess',
        ]);
    }
}
