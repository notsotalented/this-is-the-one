<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request;

/**
 * Class UpdateReleaseVueJSRequest.
 */
class UpdateReleaseVueJSRequest extends Request
{

    /**
     * The assigned Transporter for this Request
     *
     * @var string
     */
    protected $transporter = \App\Containers\ReleaseVueJS\Data\Transporters\UpdateReleaseVueJSTransporter::class;

    /**
     * Define which Roles and/or Permissions has access to this request.
     *
     * @var  array
     */
    protected $access = [
        'permissions' => '',
        'roles'       => 'admin',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     *
     * @var  array
     */
    protected $decode = [
        'id',
    ];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
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
            'id'                 => 'required|exists:releasevuejs,id',
            'name'               => 'unique:releasevuejs,name|max:40|min:3',
            'title_description'  => 'required|max:255|min:3',
            'detail_description' => 'required|string|min:3',
            'is_publish'         => 'boolean',
            'images'             => 'array',
            'images.*'           => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
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