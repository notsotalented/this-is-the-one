<?php

namespace App\Containers\Welcome\UI\WEB\Tests\Functional;

use App\Containers\Welcome\Tests\WebTestCase;


/**
 * Class welcomePageTest.
 *
 * @group welcome
 * @group web
 */
class welcomePageTest extends WebTestCase
{

    // the endpoint to be called within this test (e.g., get@v1/users)
    protected $endpoint = '/';

    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles'       => '',
    ];

    /**
     * @test
     */
    public function test_access_home_page()
    {   
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_access_home_page_elem() {

        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
    }

    public function test_display_table_function() {
        $testingUser = $this->getTestingUser();

        $response = $this->actingAs($testingUser, 'web')
                        ->get($this->endpoint);

        $response->assertStatus(200);
    }
}
