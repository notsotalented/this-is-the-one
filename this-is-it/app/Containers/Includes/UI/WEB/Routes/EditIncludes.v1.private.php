<?php

/** @var Route $router */
$router->get('include/{id}/edit', [
    'as' => 'web_includes_edit',
    'uses'  => 'Controller@edit',
    'middleware' => [
      'auth:web',
    ],
]);
