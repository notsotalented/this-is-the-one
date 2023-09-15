<?php

namespace App\Containers\Authorization\Tests\Unit;

use App\Containers\Authorization\Actions\CreatePermissionAction;
use App\Containers\Authorization\Actions\FindPermissionAction;
use App\Containers\Authorization\Actions\FindRoleAction;
use App\Containers\Authorization\Exceptions\PermissionNotFoundException;
use App\Containers\Authorization\Exceptions\RoleNotFoundException;
use App\Containers\Authorization\Tasks\DeleteRoleTask;
use App\Containers\Authorization\Tasks\DetachPermissionsFromRoleTask;
use App\Containers\Authorization\Tests\TestCase;
use App\Containers\User\Models\User;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Transporters\DataTransporter;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

/**
 * Class AuthorizationActionUnitTest.
 *
 * @group authorization
 * @group unit
 */
class AuthorizationActionUnitTest extends TestCase
{
    protected $admin;

    protected $user;

    protected $guest;

    protected $userRole;

    public function setUp(): void
    {
        parent::setUp();

        //Create User role
        $this->userRole = Role::create([
            'name' => 'user',
            'level' => 10,
            'guard_name' => 'web',
            'display_name' => 'User',
        ]);

        //Add all permissions to admin
        Role::find(1)->givePermissionTo(Permission::all());

        //Setup user role
        Role::find(2)->givePermissionTo([
            'list-users', 'search-users', 'manage-roles', 'update-users'
        ]);

        //Save Super Admin
        $this->admin = User::find(1);

        //Create user and save
        $this->user = factory(User::class)->create();
        //Add role to user
        $this->user->assignRole($this->userRole);

        //Create guest and save
        $this->guest = factory(User::class)->create();
    }


    public function testDeleteRoleTaskException()
    {
        $this->expectException(DeleteResourceFailedException::class);

        $data = Role::find(2);

        $action = \App::make(DeleteRoleTask::class);
        $action->run($data);
        //Rerun
        $action->run($data);
    }

    public function testDetachPermissionsFromRoleInputString() {
        $role = \App\Containers\Authorization\Models\Role::find(2);

        $task = \App::make(DetachPermissionsFromRoleTask::class);
        $task->run($role, 'search-users');
        
        $this->assertFalse($role->hasPermissionTo('search-users'));
    }

    public function testAuthorizationTraits() {
        \Auth::login($this->admin);
        $this->assertEquals(Auth()->getUser()->getRoleLevel(), 999);
        $this->assertEquals(Auth()->getUser(), User::find(1));

    }

    public function testFindRoleException() {
        $this->expectException(RoleNotFoundException::class);
    
        $data = [
            'id' => '99',
        ];
        $action = \App::make(FindRoleAction::class);
        $action->run(new DataTransporter($data));
    }

    public function testFindPermissionException() {
        $this->expectException(PermissionNotFoundException::class);
    
        $data = [
            'id' => '99',
        ];
        $action = \App::make(FindPermissionAction::class);
        $action->run(new DataTransporter($data));
    }

    public function testCreatePermission() {
        $data = [
            'name' => Str::random(10),
            'description' => Str::random(10),
            'display_name' => Str::random(10),  
        ];

        $action = \App::make(CreatePermissionAction::class);
        $response = $action->run(new DataTransporter($data));

        $this->assertDatabaseHas('permissions', $data);
    }
}
