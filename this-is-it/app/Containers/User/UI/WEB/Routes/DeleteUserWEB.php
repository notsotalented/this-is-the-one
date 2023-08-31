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
{
    "message": "User (4) Deleted Successfully."
}
 */

$router->get('/users/{id}/delete', [
    'as' => 'delete-page',
    'uses'       => 'Controller@showDeletePage',
    'middleware' => [
        'auth:web',
    ],
]);

$router->post('/users/{id}/delete', [
    'as' => 'post_form_delete_user',
    'uses'       => 'Controller@deleteUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);

$router->delete('/users/{id}/delete', [
    'as' => 'delete_user',
    'uses'       => 'Controller@deleteUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);


