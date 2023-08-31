<?php

/** @var Route $router */
$router->patch('include/{id}', [
    'as' => 'web_includes_update',
    'uses'  => 'Controller@update',
    'middleware' => [
      'auth:web',
    ],
]);
