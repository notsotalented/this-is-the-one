<?php

namespace App\Containers\Welcome\UI\WEB\Tests\Functional;

use App\Containers\Welcome\Tests\WebTestCase;

/**
 * Class longinltest.
 *
 * @group welcome
 * @group web
 */
class longinltest extends WebTestCase
{
    // the endpoint to be called within this test (e.g., get@v1/users)
    protected $endpoint = 'method@endpoint';

    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles'       => '',
    ];

    /**
     * @test
     */
    public function test_()
    {
        $data = [
            // 'key' => 'value',
        ];

        // send the HTTP request
        $response = $this->get('/');

        // assert the response status
        $response->assertStatus(200);

        // make other asserts here
    }

}
