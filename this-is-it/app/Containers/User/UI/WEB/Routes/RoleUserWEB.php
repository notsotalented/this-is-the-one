<?php

/**
 * @apiGroup           Users
 * @apiName            deleteUser
 * @api                {delete} /v1/users/:id Delete User
 * @apiDescription     Delete users of any type (Admin, Client...)
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiSuccessExample  {json}       Success-Response:
 * HTTP/1.1 202 OK
 */

$router->get('/role-page', [
    'as' => 'role-page',
    'uses'       => 'Controller@showRolePage',
    'middleware' => [
        'auth:web'
    ],
]);

$router->get('/role-page/{action}', [
    'as' => 'role-page-action',
    'uses'       => 'Controller@showRolePage',
    'middleware' => [
        'auth:web'
    ],
]);

$router->post('/role-page/{action}', [
    'as' => 'role-page-action',
    'uses'       => 'Controller@changePermissionToRoleWEB',
]);


