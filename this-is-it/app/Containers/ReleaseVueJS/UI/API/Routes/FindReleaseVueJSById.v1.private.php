<?php

/**
 * @apiGroup           ReleaseVueJS
 * @apiName            findReleaseVueJSById
 *
 * @api                {GET} /v1/releasevuejs/:id Endpoint title here..
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
$router->get('releasevuejs/{id}', [
    'as' => 'api_releasevuejs_find_releasevuejs_by_id',
    'uses'  => 'Controller@findReleaseVueJSById',
    'middleware' => [
      'auth:api',
    ],
]);
