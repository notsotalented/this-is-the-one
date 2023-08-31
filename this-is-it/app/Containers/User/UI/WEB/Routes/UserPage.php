<?php

/** @var Route $router */
$router->get('/users/{id}', [
    'as' => 'user-profile',
    'uses'  => 'Controller@showUserProfile',
    'middleware' => [
        'auth:web',
    ],
]);

$router->get('/users', [
    'as' => 'users-profile',
    'uses'  => 'Controller@showUsersProfile',
    'middleware' => [
        'auth:web',
    ],
]);

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


