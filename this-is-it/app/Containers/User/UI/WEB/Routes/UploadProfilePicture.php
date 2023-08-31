<?php

$router->post('/user/{id}/upload', [
    'as'   => 'profile-picture-upload',
    'uses' => 'Controller@profilePictureUpload',
]);