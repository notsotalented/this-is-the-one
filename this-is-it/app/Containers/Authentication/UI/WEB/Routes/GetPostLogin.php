<?php
/** @var Route $router */
$router->get('/login', [
    'as' => 'login',
    'uses'  => 'Controller@showLoginPageFox',
]);

$router->post('/login', [
    'as' => 'post_login_form',
    'uses' => 'Controller@loginFox',
    'middleware' => [
        'throttle: 5, 1'
    ],
]);