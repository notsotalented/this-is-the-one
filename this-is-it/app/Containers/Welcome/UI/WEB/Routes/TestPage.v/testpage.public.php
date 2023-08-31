<?php

/** @var Route $router */
$router->get('/test-page/{uri}/{id}', [
    'as' => 'test-page-uri-id',
    'uses'  => 'Controller@showTest',
]);

$router->get('/test-page', [
    'as' => 'test-page',
    'uses'  => 'Controller@showTest',
]);

$router->get('/test-page/{uri}', [
    'as' => 'test-page-uri',
    'uses'  => 'Controller@showTest',
]);
