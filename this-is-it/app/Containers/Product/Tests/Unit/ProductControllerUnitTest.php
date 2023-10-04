<?php

namespace App\Containers\Product\Tests\Unit;

use App\Containers\Product\Models\Product;
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
    $response->assertSessionMissing('products');
    $response = $this->actingAs($this->user)->get($this->public_endpoint)->assertStatus(200);
  }

  /**
   * A test case for the `testGetSpecificProduct` method.
   */
  public function testGetSpecificProduct() {

    //Assert 200 admin get product id 1
    $response = $this->actingAs($this->admin)->get($this->public_endpoint . '/1');
    $response->assertStatus(200);
    $response->assertSee(Product::find(1)->name);

    //Assert 302 admin get product id 2
    $response = $this->from($this->public_endpoint)->actingAs($this->admin)->get($this->public_endpoint . '/2');
    $response->assertStatus(302);
    $this->assertEquals(session('errors')->getBag('default')->first(),'Product not found!');

    $response->assertRedirect($this->public_endpoint);

  }

  public function testGetAllPersonalProduct() {
    //Get access to personal product without login and assert 401
    $response = $this->get('users/1' . $this->public_endpoint)->assertStatus(401);

    //Get access to personal product with login and assert 200
    $response = $this->actingAs($this->user)->get('users/1' . $this->public_endpoint)->assertStatus(200);
    //See no product since user 1 doesn't have any products
    $response->assertDontSee(Product::find(1)->name);

    //Get access to personal product with login and assert 200
    $response = $this->actingAs($this->admin)->get('users/2' . $this->public_endpoint)->assertStatus(200);
    //See product id 1 since user 2 has product
    $response->assertSee(Product::find(1)->name);
  }


  /**
   * Test the functionality of the "testGetSpecificPersonalProduct" function.
   *
   * This function performs a series of tests to verify the behavior of the
   * "testGetSpecificPersonalProduct" function. It tests the ability to access
   * personal products with and without logging in, and asserts the expected
   * status codes and error messages.
   *
   * @throws \Exception if an error occurs during the test
   */
  public function testGetSpecificPersonalProduct() {
    //Get access to personal product without login and assert 401
    $response = $this->get('users/1' . $this->public_endpoint . '/1')->assertStatus(401);

    //Get access to personal product with login and assert 302 since user 1 doesn't have product 1
    $response = $this->actingAs($this->user)->get('users/1' . $this->public_endpoint . '/1')->assertStatus(302);
    //Assert message
    $this->assertEquals(session('errors')->getBag('default')->first(),'Product not found!');

    //Get access to personal product (ID = 99) with login and assert 302
    $response = $this->actingAs($this->user)->get('users/1' . $this->public_endpoint . '/99')->assertStatus(302);
    //Assert message
    $this->assertEquals(session('errors')->getBag('default')->first(),'Product not found!');

    //Get access to personal product with user 99 with login and assert 302
    $response = $this->actingAs($this->user)->get('users/99' . $this->public_endpoint . '/1')->assertStatus(302);
    //Assert message
    $this->assertEquals(session('errors')->getBag('default')->first(),'The requested Resource was not found.');

    //Get access to personal product with user 2 to product 1 with login and assert 200
    $response = $this->actingAs($this->user)->get('users/2' . $this->public_endpoint . '/1')->assertStatus(200);
    //See product id 1 since user 2 has product
    $response->assertSee(Product::find(1)->name);
  }

  public function testAccessAddProductPage() {
    //Access add page of user 2 with user 2 and assert 200
    $response = $this->actingAs($this->user)->get('users/2' . $this->public_endpoint . '/add')->assertStatus(200);
    $response->assertSee('Add Product');

    //Access add page with mismatch user and owner and assert 403
    $response = $this->actingAs($this->admin)->get('users/2' . $this->public_endpoint . '/add')->assertStatus(403);

  }
}
