<?php

/**
 * @apiGroup           ReleaseVueJS
 * @apiName            createReleaseVueJS
 *
 * @api                {POST} /v1/releasevuejs Endpoint title here..
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
$router->post('releasevuejs', [
    'as' => 'api_releasevuejs_create_release_vue_j_s',
    'uses'  => 'Controller@createReleaseVueJS',
    'middleware' => [
      'auth:api',
    ],
]);
