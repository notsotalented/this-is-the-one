<?php

namespace App\Containers\Welcome\Tests\Unit;

use App\Containers\Welcome\Tests\TestCase;
use App\Containers\User\Models\User;


/**
 * Class unitTest.
 *
 * @group welcome
 * @group unit
 */
class HomePageTest extends TestCase
{
    public $admin_user;

    protected $endpoint = '/';

    protected $access = [
        'roles' => 'admin',
        'permissions' => 'search-users',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->admin_user = $this->getTestingUser();
    }

    public function test_access_home(): void
    {
        //TEST ACCESS WITH LOGIN


        $response = $this->actingAs($this->admin_user)->get($this->endpoint);

        $response->assertStatus(200);
    }

    public function test_access_home_without_login(): void
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
    }
}

