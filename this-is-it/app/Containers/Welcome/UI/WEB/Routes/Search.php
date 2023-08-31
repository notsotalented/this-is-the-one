<?php

/** @var Route $router */
$router->get('/search', [
    'as' => 'searching',
    'uses'  => 'Controller@search',
]);

