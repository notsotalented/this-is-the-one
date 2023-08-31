<?php

namespace App\Containers\User\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request;

/**
 * Class RegisterUserRequest.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class ConfirmUserRequestWEB extends Request
{

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'permissions' => '',
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
            'email'    => 'required|email|max:40',
            'password' => 'required|min:5|max:30',
            'name'     => 'max:50',
            'gender'   => '',
            'birth' => 'date',
            'password_new' => 'required|min:5|max:30',
        ];
    }

    /**
     * @return  bool
     */
    public function authorize()
    {
        return $this->check([
            'hasAccess',
        ]);
    }
}
