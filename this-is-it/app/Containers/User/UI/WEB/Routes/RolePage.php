<?php

/** @var Route $router */
$router->get('/create-role-page', [
    'as' => 'create-role',
    'uses'  => 'Controller@showCreateRolePage',
    'middleware' => [
        'auth:web',
    ],
]);

$router->post('/create-role-page', [
    'as' => 'post-form-create-role',
    'uses'  => 'Controller@createNewRole',
    'middleware' => [
        'auth:web',
    ],
]);

$router->get('/delete-role/{id}', [
    'as' => 'delete-role',
    'uses'  => 'Controller@deleteRole',
    'middleware' => [
      'auth:web',
    ],
]);