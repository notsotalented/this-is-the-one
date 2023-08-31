<?php

/**
 * @apiGroup           Includes
 * @apiName            getAllIncludes
 *
 * @api                {GET} /v1/includes Endpoint title here..
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
$router->get('includes', [
    'as' => 'api_includes_get_all_includes',
    'uses'  => 'Controller@getAllIncludes',
    'middleware' => [
      'auth:api',
    ],
]);
