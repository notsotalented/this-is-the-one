<?php

namespace App\Containers\Product\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request;

/**
 * Class AddProductRequest.
 */
class AddProductRequest extends Request
{

    /**
     * The assigned Transporter for this Request
     *
     * @var string
     */
    // protected $transporter = \App\Ship\Transporters\DataTransporter::class;

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
        // 'id',
    ];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
     * validation rules on them and allows accessing them like request data.
     *
     * @var  array
     */
    protected $urlParameters = [
         'userId',
    ];

    /**
     * @return  array
     */
    public function rules()
    {
        return [
            'userId' => 'required',
            'name' => 'required|min:5|max:255',
            'brand' => 'required',
            'description' => 'required|max:255',
            'quantity' => 'required|integer',
            'image' => 'required',
            'image*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
            // '{user-input}' => 'required|max:255',
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
