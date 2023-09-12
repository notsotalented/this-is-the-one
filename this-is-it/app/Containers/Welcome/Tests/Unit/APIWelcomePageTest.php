<?php

namespace App\Containers\Welcome\Tests\Unit;

use App\Containers\Welcome\Tests\TestCase;

/**
 * Class APIWelcomePageTest.
 *
 * @group welcome
 * @group unit
 */
class APIWelcomePageTest extends TestCase
{
    /**
     * @test
     */
    public function testApiWelcome()
    {
        $response = $this->call('GET', route('api_welcome_root_page'));
        $response->assertStatus(200);
        $response->assertSee('Welcome to Apiato');

        $response = $this->call('GET', route('v1_api_landing_route'));
        $response->assertStatus(200);
        $response->assertSee('Welcome to Apiato (API V1)');
    }
}
