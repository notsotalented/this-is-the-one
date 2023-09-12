<?php

$router->post('/users/{id}/upload', [
    'as'   => 'profile-picture-upload',
    'uses' => 'Controller@profilePictureUpload',
]);