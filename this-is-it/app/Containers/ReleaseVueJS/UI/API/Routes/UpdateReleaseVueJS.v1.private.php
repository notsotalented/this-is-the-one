<?php

/**
 * @apiGroup           ReleaseVueJS
 * @apiName            updateReleaseVueJS
 *
 * @api                {PATCH} /v1/releasevuejs/:id Endpoint title here..
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
$router->patch('releasevuejs/{id}', [
    'as' => 'api_releasevuejs_update_release_vue_j_s',
    'uses'  => 'Controller@updateReleaseVueJS',
    'middleware' => [
      'auth:api',
    ],
]);
