<?php

/** @var Route $router */
$router->get('products', [
    'as' => 'web_product_get_all_products',
    'uses'  => 'Controller@getAllProducts',
    'middleware' => [
      'auth:web',
    ],
]);
