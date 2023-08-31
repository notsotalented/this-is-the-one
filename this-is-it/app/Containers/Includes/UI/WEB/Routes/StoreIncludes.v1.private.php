<?php

/** @var Route $router */
$router->post('include/store', [
    'as' => 'web_includes_store',
    'uses'  => 'Controller@store',
    'middleware' => [
      'auth:web',
    ],
]);
