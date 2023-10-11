<?php

/**
 * @apiGroup           ReleaseVueJS
 * @apiName            getAllReleaseVueJs
 *
 * @api                {GET} /v1/releasevuejs Endpoint title here..
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
$router->get('releasevuejs', [
    'as' => 'api_releasevuejs_get_all_releasevuejs',
    'uses'  => 'Controller@getAllReleaseVueJs',
    'middleware' => [
      'auth:api',
    ],
]);
