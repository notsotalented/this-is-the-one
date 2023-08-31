<?php

/** @var Route $router */
$router->get('include/{id}', [
    'as' => 'web_includes_show',
    'uses'  => 'Controller@show',
    'middleware' => [
      'auth:web',
    ],
]);
