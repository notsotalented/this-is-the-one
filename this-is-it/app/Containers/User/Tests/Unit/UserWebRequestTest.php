<?php

namespace App\Containers\User\Tests\Unit;

use App\Containers\User\Tests\TestCase;

/**
 * Class UserWebRequestTest.
 *
 * @group user
 * @group unit
 */
class UserWebRequestTest extends TestCase
{
    protected $admin;

    protected $client;

    protected $guest;

    public function setUp(): void
    {
        parent::setUp();

        //Create client role
        $role = factory(Role::class)->create([
            'name' => 'client',
            'display_name' => 'Client Role',
            'description' => 'Client',
            'level' => 10,
        ]);
        //Create admin user
        $this->admin = factory(User::class)->create();
        $this->admin->assignRole('admin');
        //Create client user
        $this->client = factory(User::class)->create();
        $this->client->assignRole('client');
        //Create guest user
        $this->guest = factory(User::class)->create();
    }
}
