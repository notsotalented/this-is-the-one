<?php

/** @var Route $router */

$router->get('/dashboard', [
    'as' => 'list-page',
    'uses' => 'listProcessing@displayUsers',
    'middleware' => [
        'auth:web',
    ],
]);