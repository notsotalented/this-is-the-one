<?php

/** @var Route $router */
$router->get('logout', [
    'as' => 'logout',
    'uses'  => 'Controller@logoutFox',
    'middleware' => [
        'auth:web'
    ],
]);
