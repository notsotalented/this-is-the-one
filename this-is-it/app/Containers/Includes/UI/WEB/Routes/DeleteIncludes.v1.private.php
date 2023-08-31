<?php

/** @var Route $router */
$router->delete('include/{id}', [
    'as' => 'web_includes_delete',
    'uses'  => 'Controller@delete',
    'middleware' => [
      'auth:web',
    ],
]);
