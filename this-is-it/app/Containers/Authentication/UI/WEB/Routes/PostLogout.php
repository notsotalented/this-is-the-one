<?php

/** @var Route $router */
$router->get('logout', [
    'as' => 'logout',
    'uses'  => 'Controller@logoutNormie',
    'middleware' => [
        'auth:web'
    ],
]);
