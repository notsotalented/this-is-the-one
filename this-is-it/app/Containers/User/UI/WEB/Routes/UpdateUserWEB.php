<?php

/**
 * @apiGroup           Users
 * @apiName            updateUser
 * @api                {put} /v1/users/:id Update User
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiParam           {String}  password (optional)
 * @apiParam           {String}  name (optional)
 *
 * @apiUse             UserSuccessSingleResponse
 */


$router->get('/users/{id}/update', [
    'as' => 'update-page',
    'uses'  => 'Controller@showUpdatePageWithInfo',
    'middleware' => [
        'auth:web',
    ],
]);

$router->post('/users/{id}/update', [
    'as' => 'update_user',
    'uses'       => 'Controller@updateUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);

$router->put('/users/{id}/update', [
    'as' => 'update_user',
    'uses'       => 'Controller@updateUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);

$router->post('/users/{id}/power-update', [
    'as' => 'power-update-user',
    'uses'       => 'Controller@powerUpdateUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);

$router->put('/users/{id}/power-update', [
    'as' => 'power-update-user',
    'uses'       => 'Controller@powerUpdateUserWEB',
    'middleware' => [
        'auth:web',
    ],
]);
