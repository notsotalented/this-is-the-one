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

    public function testSayWelcome() {
        $this->actingAs($this->admin)->get('/user')->assertSee('Welcome Anonymous User :)');
    }

    public function testShowRegisterPage() {
        $this->actingAs($this->admin)->get('/register')->assertSee('Register');
        $power = $this->actingAs($this->admin)->get('/register-power');
        $power->assertSee('Register');

        $expectation = \App::make(GetAllRolesAction::class)->run();
        $power->assertSee($expectation);
    }

    public function testRegister() {
        $input = [
            'email' => 'test@gmail.com',
            'password' => '123456',
            'gender' => 'male',
            'birth' => '1990-01-01',
            'name' => 'test',
        ];

        //Success
        $response = $this->postJson('/register', $input);
        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Register successfully!');
        $response->assertRedirect('/home');
        $this->assertEquals($input['email'], User::where('email', $input['email'])->first()->email);
        //Redeclare
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
    public function testRegisterPowerCheck() {
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
            'roles_ids' => ['2'], //Admin = 1; Client = 2
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

    public function testShowDeletePage() {
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

    public function testShowRolePage() {
        $response = $this->get('role-page/attach');
        $response->assertStatus(401);

        $response = $this->actingAs($this->guest)->get('role-page/attach');
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->get('role-page/attach');
        $response->assertStatus(200);

        $content = $response->getOriginalContent();

        $this->assertEquals(Collection::make($content['roles']), \App\Containers\Authorization\Models\Role::all());
    }

    public function testChangePermissionsToRole() {
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

    public function testDeleteUser() {
        $target = factory(User::class)->create();

        $url = 'users/' . $target->id . '/delete';

        $data = [
            'id' => $target->id,
        ];

        $falseData = [
            'id' => 6391.
        ];

        $response = $this->actingAs($this->guest)->postJson($url, $data);
        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $target->id]);
        $response = $this->actingAs($this->admin)->postJson($url, $data);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertSoftDeleted('users', ['id' => $target->id]);

        $response = $this->actingAs($this->admin)->postJson($url, $falseData);
        $response->assertStatus(404);
    }
}
