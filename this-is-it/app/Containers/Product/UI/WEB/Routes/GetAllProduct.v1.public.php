<?php

/** @var Route $router */
$router->get('products', [
    'as' => 'web_product_get_all_products',
    'uses'  => 'Controller@getAllProducts',
    /* No guard here
    'middleware' => [
      'auth:web',
    ],
    */
]);

$router->get('products/{id}', [
    'as' => 'web_product_get_specific_product',
    'uses' => 'Controller@showSpecificProduct',
    /* No guard here
    'middleware' => [
      'auth:web',
    ]
    */
]);
