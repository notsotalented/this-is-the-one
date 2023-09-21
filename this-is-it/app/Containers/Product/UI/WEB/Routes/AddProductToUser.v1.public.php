<?php

/** @var Route $router */
$router->get('users/{userId}/products/add', [
  'as' => 'web_product_show_add_form',
  'uses'  => 'Controller@addProductsPage',
  'middleware' => [
    'auth:web',
  ],
]);

$router->post('users/{userId}/products/add-product', [
  'as' => 'web_product_add_to_user',
  'uses'  => 'Controller@addProductToUser',
  'middleware' => [
    'auth:web',
  ]
]);
