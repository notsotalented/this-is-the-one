<?php

/** @var Route $router */
$router->get('include', [
    'as' => 'web_includes_index',
    'uses'  => 'Controller@index',
    'middleware' => [
      'auth:web',
    ],
]);
