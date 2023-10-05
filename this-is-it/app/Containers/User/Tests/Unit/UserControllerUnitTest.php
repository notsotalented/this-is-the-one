<?php

namespace App\Containers\User\Tests\Unit;

use App\Containers\Authorization\Actions\CreateRoleAction;
use App\Containers\Authorization\Actions\GetAllPermissionsAction;
use App\Containers\Authorization\Actions\GetAllRolesAction;
use App\Containers\Authorization\Tasks\GetAllPermissionsTask;
use App\Containers\User\Models\User;
use App\Containers\User\Tests\TestCase;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class UserControllerUnitTest.
 *
 * @group user
 * @group unit
 */
class UserControllerUnitTest extends TestCase
{
  protected $admin;

  protected $client;

  protected $guest;

  protected $admin_access = [
    'role' => 'admin',
    'permission' => ['access-dashboard', 'list-users', 'search-users', 'update-users', 'delete-users', 'manage-roles', 'manage-admins-access'],
  ];

  protected $client_access = [
    'role' => 'client',
    'permission' => ['access-dashboard', 'list-users', 'search-users', 'update-users', 'delete-users', 'manage-roles'],
  ];

  public function setUp(): void
  {
    parent::setUp();

    $this->admin = factory(User::class)->create();
    $this->admin->assignRole($this->admin_access['role']);
    $this->admin->syncPermissions($this->admin_access['permission']);

    $data = [
      'name' => 'client',
      'display_name' => 'Client',
      'level' => '10',
      'guard_name' => 'web',
    ];
    \App::make(CreateRoleAction::class)->run(new DataTransporter($data));

    $this->client = factory(User::class)->create();
    $this->client->assignRole($this->client_access['role']);
    $this->client->syncPermissions($this->client_access['permission']);

    $this->guest = factory(User::class)->create();
  }

/**
 * Test the "sayWelcome" method.
 *
 * @return void
 */
public function testSayWelcome()
{
    // Set the authenticated user as the admin
    $this->actingAs($this->admin);

    // Send a GET request to the '/user' endpoint
    $response = $this->get('/user');

    // Assert that the response contains the welcome message
    $response->assertSee('Welcome Anonymous User :)');
}

/**
 * Test the show register page functionality.
 *
 * @return void
 */
public function testShowRegisterPage()
{
    // Act as admin and get the register page
    $response = $this->actingAs($this->admin)->get('/register');

    // Assert that the page contains 'Register'
    $response->assertSee('Register');

    // Act as admin and get the register power page
    $power = $this->actingAs($this->admin)->get('/register-power');

    // Assert that the page contains 'Register'
    $power->assertSee('Register');

    // Get the expectation from the GetAllRolesAction class
    $expectation = \App::make(GetAllRolesAction::class)->run();

    // Assert that the register power page contains the expectation
    $power->assertSee($expectation);
}

/**
 * Test the registration functionality.
 *
 * @return void
 */
public function testRegister()
{
    // Set up the input data
    $input = [
        'email' => 'test@gmail.com',
        'password' => '123456',
        'gender' => 'male',
        'birth' => '1990-01-01',
        'name' => 'test',
    ];

    // Success scenario
    $response = $this->postJson('/register', $input);
    $response->assertStatus(302);
    $response->assertSessionHas('status', 'Register successfully!');
    $response->assertRedirect('/home');
    $this->assertEquals($input['email'], User::where('email', $input['email'])->first()->email);

    // Redefine the input data to test duplicate email scenario
    $response = $this->postJson('/register', $input);
    $response->assertStatus(422);
    $response->assertSeeText('The email has already been taken');
}

  /**
   * Test the register power check.
   *
   *
   * @return void
   */
  public function testRegisterPowerCheck()
  {
    $input = [
      'email' => 'test@gmail.com',
      'password' => '123456',
      'gender' => 'male',
      'birth' => '1990-01-01',
      'name' => 'test',
    ];

    $input2 = [
      'email' => 'test1@gmail.com',
      'password' => '123456',
      'gender' => 'male',
      'birth' => '1990-01-01',
      'name' => 'test',
      'roles_ids' => ['2'],
      //Admin = 1; Client = 2
    ];

    //Not authorized
    $this->actingAs($this->guest)->postJson('/register-power', $input)->assertStatus(403);
    //Authorized
    $response = $this->actingAs($this->client)->postJson('/register-power', $input);
    $response->assertStatus(302);
    $response->assertSessionHas('status', 'Register power user successfully!');
    $response->assertRedirect('/home');
    $this->assertEquals($input['email'], User::where('email', $input['email'])->first()->email);
    //Not authorized
    $response = $this->actingAs($this->client)->postJson('/register-power', $input2);
    $response->assertStatus(403);
    //Authorized
    $response = $this->actingAs($this->admin)->postJson('/register-power', $input2);
    $response->assertStatus(302);
    $response->assertSessionHas('status', 'Register power user successfully!');
    $response->assertRedirect('/home');
    $this->assertEquals($input2['email'], User::where('email', $input2['email'])->first()->email);
    $this->assertTrue(User::where('email', $input2['email'])->first()->hasRole('client'));
    //Duplicated
    $response = $this->actingAs($this->client)->postJson('/register-power', $input);
    $response->assertStatus(422);
    $response->assertSeeText('The email has already been taken');
  }

  public function testShowDeletePage()
  {
    $response = $this->actingAs($this->admin)->get('users/1/delete');
    $response->assertStatus(403);

    $response = $this->actingAs($this->admin)->get('users/' . $this->client->id . '/delete');
    $response->assertStatus(200);
    $response->assertSee('Confirm Delete');

    $response = $this->actingAs($this->guest)->get('users/' . $this->client->id . '/delete');
    $response->assertStatus(403);

    $response = $this->actingAs($this->client)->get('users/' . $this->admin->id . '/delete');
    $response->assertStatus(403);
  }

  public function testShowRolePage()
  {
    $response = $this->get('role-page/attach');
    $response->assertStatus(401);

    $response = $this->actingAs($this->guest)->get('role-page/attach');
    $response->assertStatus(403);

    $response = $this->actingAs($this->admin)->get('role-page/attach');
    $response->assertStatus(200);
  }

  public function testChangePermissionsToRole()
  {
    //Not Authorized
    $response = $this->actingAs($this->guest)->postJson('role-page/attach', [
      'role_id' => '2',
      'permission_id' => ['1', '2'],
    ]);
    $response->assertStatus(403);

    //Fail attempt to alter admin's permissions
    $response = $this->actingAs($this->admin)->postJson('role-page/attach', [
      'role_id' => '1',
      'permission_id' => ['1', '2'],
    ]);
    $response->assertStatus(403);

    //Authorized attach
    $role_id = 2;
    $permissions_ids = [1, 3, 4, 5];
    $detach_ids = [1, 5];

    $response = $this->actingAs($this->admin)->postJson('role-page/attach', [
      'role_id' => $role_id,
      'permissions_ids' => $permissions_ids,
      'action' => 'attach',
    ]);

    $response->assertStatus(200);
    $response->assertSeeText('Attached successfully!');
    foreach ($permissions_ids as $permission) {
      $this->assertTrue(Role::find($role_id)->hasPermissionTo(Permission::find($permission)->name));
    }

    //Not authorized detach
    $response = $this->actingAs($this->client)->postJson('role-page/detach', [
      'role_id' => $role_id,
      'permissions_ids' => $permissions_ids,
      'action' => 'detach',
    ]);

    $response->assertStatus(403);

    //Authorized detach
    $response = $this->actingAs($this->admin)->postJson('role-page/detach', [
      'role_id' => $role_id,
      'permissions_ids' => $detach_ids,
      'action' => 'detach',
    ]);

    $response->assertStatus(200);
    $response->assertSeeText('Detached successfully!');
    foreach ($detach_ids as $detach) {
      $this->assertFalse(Role::find($role_id)->hasPermissionTo(Permission::find($detach)->name));
    }
    $check_true = array_diff($permissions_ids, $detach_ids);
    foreach ($check_true as $true) {
      $this->assertTrue(Role::find($role_id)->hasPermissionTo(Permission::find($true)->name));
    }
  }

  public function testDeleteUser()
  {
    $target = factory(User::class)->create();

    $url = 'users/' . $target->id . '/delete';

    $data = [
      'id' => $target->id,
    ];

    $falseData = [
      'id' => 6391
    ];

    $target_2 = factory(User::class)->create();

    $response = $this->actingAs($this->guest)->postJson($url, $data);
    $response->assertStatus(403);

    $this->assertDatabaseHas('users', ['id' => $target->id]);
    $response = $this->actingAs($this->admin)->postJson($url, $data);
    $response->assertStatus(302);
    $response->assertRedirect('/home');
    $this->assertSoftDeleted('users', ['id' => $target->id]);

    $response = $this->actingAs($this->admin)->postJson($url, $falseData);
    $response->assertStatus(404);

    $this->guest->givePermissionTo('delete-users');

    $response = $this->actingAs($this->guest)->postJson('users/' . $target_2->id . '/delete', ['id' => $this->client->id]);
    $response->assertStatus(302);
    $response->assertRedirect('/home');
    $this->assertSoftDeleted('users', ['id' => $target_2->id]);

    //Test Terminate oneself: create user
    $target_3 = factory(User::class)->create();
    $this->assertDatabaseHas('users', ['id' => $target_3->id]);
    //Delete user
    $response = $this->actingAs($target_3)->postJson('users/' . $target_3->id . '/delete', ['id' => $target_3->id]);
    $response->assertStatus(302);
    $response->assertRedirect('/home');
    $this->assertSoftDeleted('users', ['id' => $target_3->id]);
  }

  public function testShowCreateRolePage()
  {
    $this->get('create-role-page')->assertStatus(401);
    $this->actingAs($this->guest)->get('create-role-page')->assertStatus(403);

    $this->actingAs($this->client)->get('create-role-page')->assertStatus(200);
    $this->actingAs($this->admin)->get('create-role-page')->assertStatus(200);
  }

  /**
   * Test the creation of a new role.
   *
   * @return void
   */
  public function testCreateNewRoleAndDeleteRole()
  {
    $data_role0 = [
      'name' => 'test0',
      'display_name' => 'Tester Role',
      'description' => 'Do Test',
      'level' => '0',
    ];
    $data_role5 = [
      'name' => 'test5',
      'display_name' => 'Tester Role',
      'description' => 'Do Test',
      'level' => '5',
    ];
    $data_role10 = [
      'name' => 'test10',
      'display_name' => 'Tester Role',
      'description' => 'Do Test',
      'level' => '10',
    ];

    //Not authorized
    $this->actingAs($this->guest)->postJson('create-role-page', $data_role5)->assertStatus(403);
    //Authorized add level 5
    $response = $this->actingAs($this->client)->postJson('create-role-page', $data_role5);
    $response->assertStatus(200);

    $response->assertSee('Role ' . $data_role5['display_name'] . ' created successfully!');
    $this->assertDatabaseHas('roles', $data_role5);
    //Not authorized add level 10
    $this->actingAs($this->client)->postJson('create-role-page', $data_role10)->assertStatus(403);
    $this->assertDatabaseMissing('roles', $data_role10);
    //Authorized add level 10
    $response = $this->actingAs($this->admin)->postJson('create-role-page', $data_role10);
    $response->assertStatus(200);
    $response->assertSee('Role ' . $data_role10['display_name'] . ' created successfully!');
    $this->assertDatabaseHas('roles', $data_role10);
    //Duplicated
    $this->actingAs($this->admin)->postJson('create-role-page', $data_role5)->assertStatus(422);

    $target_id_10 = Role::findByName($data_role10['name'])->id;
    //Delete not authorized
    $this->actingAs($this->client)->get('delete-role/' . $target_id_10)->assertStatus(403);
    $this->assertDatabaseHas('roles', ['id' => $target_id_10]);
    //Delete authorized
    $response = $this->actingAs($this->admin)->get('delete-role/' . $target_id_10)->assertStatus(200);
    $response->assertSee('Role ' . $data_role10['display_name'] . ' deleted!');
    $this->assertDatabaseMissing('roles', ['id' => $target_id_10]);

    $this->actingAs($this->admin)->postJson('create-role-page', $data_role0);
    $target_id_0 = Role::findByName($data_role0['name'])->id;
    $this->assertDatabaseHas('roles', ['id' => $target_id_0]);
    $this->guest->givePermissionTo('manage-roles');
    //Delete without role
    $response = $this->actingAs($this->guest)->get('delete-role/' . $target_id_0)->assertStatus(200);
    $response->assertSee('Role ' . $data_role0['display_name'] . ' deleted!');
    $this->assertDatabaseMissing('roles', ['id' => $target_id_0]);
  }

  public function testProfilePictureUpload()
  {
    //Create and upload first file
    $file = UploadedFile::fake()->image('avatars.jpg');

    $response = $this->actingAs($this->admin)->json('POST', 'users/' . $this->admin->id . '/upload', [
      'photo' => $file,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('users/' . $this->admin->id);
    $response->assertSessionHas('status', 'Uploaded profile picture successfully. File: ' . $this->admin->id . '-' . $response->getSession()->all()['time'] . '.jpg');
    //Assert first file exist
    $first_file = User::find($this->admin->id)->social_avatar;
    Storage::assertExists('public/uploads/photos/' . User::find($this->admin->id)->social_avatar);

    //Create and upload second file
    $file = UploadedFile::fake()->image('avatars_123.jpg');

    //Simulate different time
    sleep(1);
    $response_2 = $this->actingAs($this->admin)->json('POST', 'users/' . $this->admin->id . '/upload', [
      'photo' => $file,
    ]);

    $response_2->assertStatus(302);
    $response_2->assertRedirect('users/' . $this->admin->id);
    $response_2->assertSessionHas('status', 'Uploaded profile picture successfully. File: ' . $this->admin->id . '-' . $response_2->getSession()->all()['time'] . '.jpg');

    //Assert second file exist
    $second_file = $this->admin->social_avatar;
    Storage::assertExists('public/uploads/photos/' . User::find($this->admin->id)->social_avatar);

    //Assert first file not exist
    Storage::assertMissing('public/uploads/photos/' . $first_file);
  }
}
