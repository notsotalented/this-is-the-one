<?php

$router->post('/logout-admin', [
    'as'   => 'post_admin_logout_form',
    'uses' => 'Controller@logoutAdmin',
    'domain' => 'admin.'. parse_url(\Config::get('app.url'))['host'],
]);
