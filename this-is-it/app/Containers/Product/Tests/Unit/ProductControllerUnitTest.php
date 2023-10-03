<?php

namespace App\Containers\Product\Tests\Unit;

use App\Containers\Product\Tests\TestCase;
use App\Containers\User\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class ProductControllerUnitTest.
 *
 * @group product
 * @group unit
 */
class ProductControllerUnitTest extends TestCase
{
  protected $public_endpoint = "/products";

  protected $admin;

  protected $user;

  public function setUp(): void
  {
    parent::setUp();

    //Attach permission to admin role
    Role::findByName('admin')->syncPermissions(Permission::all());
    //Assign this->admin to admin and assign role
    $this->admin = User::find(1);
    $this->admin->assignRole('admin');

    //Factory create user
    $this->user = factory(User::class)->create([
      'name' => 'Test User',
      'email' => '9hQHb@example.com',
      'password' => bcrypt('password'),
    ]);
  }

  /**
   * Test admin access endpoints function.
   * @runTestInSeparateProcess
   * @return void
   */
  public function testGetAllProducts()
  {
    //Assert 200 admin access endpoint
    $response = $this->actingAs($this->admin)->get('users/1' . $this->public_endpoint);
    $response->assertStatus(200);
    $response = $this->actingAs($this->user)->get($this->public_endpoint)->assertStatus(200);
  }
}
