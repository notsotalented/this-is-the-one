<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Containers\User\Models\User;
use App\Ship\Parents\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DeleteUserRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteUserRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'permissions' => 'delete-users',
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
        'id',
    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:users,id',
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        //Special check for assmin
        if($this->id == '1') return false;

        //IF USER_LEVEL <= TARGET_LEVEL -> FALSE
        $id = Auth::user()->id;

        $user_role = User::findOrFail($id)->roles()->get();

        if ($user_role->first() == null) {
            $user_level = -1;
        }
        else {
            $user_level = 0;
            foreach ($user_role as $item) {
                if ($item->level > $user_level) $user_level = $item->level;
            }
        }

        $target_role = User::findOrFail($this->id)->roles()->get();

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

        return $this->check([
            'hasAccess|isOwner',
        ]);
    }
}
