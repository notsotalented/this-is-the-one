<?php
/** @var Route $router */
$router->get('/register', [
    'as' => 'register',
    'uses'  => 'Controller@showRegisterPage',
]);

$router->post('/register', [
    'as' => 'post_register_form',
    'uses'  => 'Controller@registerCheck',
]);

$router->get('/register-power', [
    'as' => 'register-power',
    'uses'  => 'Controller@showRegisterPowerPage',
]);

$router->post('/register-power', [
    'as' => 'post-register-power-form',
    'uses'  => 'Controller@registerPowerCheck',
]);
