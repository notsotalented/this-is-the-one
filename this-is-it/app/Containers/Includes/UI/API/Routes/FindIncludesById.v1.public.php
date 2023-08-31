<?php

/**
 * @apiGroup           Includes
 * @apiName            findIncludesById
 *
 * @api                {GET} /v1/includes/:id Endpoint title here..
 * @apiDescription     Endpoint description here..
 *
 * @apiVersion         1.0.0
 * @apiPermission      none
 *
 * @apiParam           {String}  parameters here..
 *
 * @apiSuccessExample  {json}  Success-Response:
 * HTTP/1.1 200 OK
{
  // Insert the response of the request here...
}
 */

/** @var Route $router */
$router->get('includes/{id}', [
    'as' => 'api_includes_find_includes_by_id',
    'uses'  => 'Controller@findIncludesById',
    'middleware' => [
      'auth:api',
    ],
]);
