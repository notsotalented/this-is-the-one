<?php

/** @var Route $router */
$router->get('users/{userId}/products', [
  'as' => 'web_product_show_all_personal',
  'uses'  => 'Controller@showAllPersonalProducts',
  'middleware' => [
    'auth:web',
  ],
]);

$router->get('users/{userId}/products/{id}', [
  'as' => 'web_product_show_specific_personal',
  'uses' => 'Controller@showSpecificPersonalProduct',
  'middleware' => [
    'auth:web',
  ],
]);