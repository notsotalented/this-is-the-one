<?php

/** @var Route $router */

$router->get('/', [
    'as'   => 'homepage',
    'uses' => 'Controller@showWelcomePage',
]);

$router->get('/home', [
    'as'   => 'home',
    'uses' => 'Controller@showWelcomePage',
]);
