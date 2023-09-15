<?php

/** @var Route $router */
$router->get('forget-password', [
    'as' => 'web_authentication_reset_password',
    'uses'  => 'Controller@resetPassword',
]);
