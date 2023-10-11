<?php

/**
 * @apiGroup           ReleaseVueJS
 * @apiName            deleteReleaseVueJS
 *
 * @api                {DELETE} /v1/releasevuejs/:id Endpoint title here..
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
$router->delete('releasevuejs/{id}', [
    'as' => 'api_releasevuejs_delete_release_vue_j_s',
    'uses'  => 'Controller@deleteReleaseVueJS',
    'middleware' => [
      'auth:api',
    ],
]);
