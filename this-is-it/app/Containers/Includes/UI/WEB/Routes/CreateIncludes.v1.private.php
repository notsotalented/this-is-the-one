<?php

/** @var Route $router */
$router->get('include/create', [
    'as' => 'web_includes_create',
    'uses'  => 'Controller@create',
    'middleware' => [
      'auth:web',
    ],
]);
