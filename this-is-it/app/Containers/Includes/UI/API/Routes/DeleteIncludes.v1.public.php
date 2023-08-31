<?php

/**
 * @apiGroup           Includes
 * @apiName            deleteIncludes
 *
 * @api                {DELETE} /v1/includes/:id Endpoint title here..
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
$router->delete('includes/{id}', [
    'as' => 'api_includes_delete_includes',
    'uses'  => 'Controller@deleteIncludes',
    'middleware' => [
      'auth:api',
    ],
]);
