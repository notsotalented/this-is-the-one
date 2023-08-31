<?php

namespace App\Containers\Authorization\Tests\Unit;


use Spatie\Permission\Models\Permission;
use App\Containers\Authorization\Tests\TestCase;
use App\Containers\User\Models\User;

/**
 * Class RoleAndPermissionsTest.
 *
 * @group authorization
 * @group unit
 */
class RoleAndPermissionsTest extends TestCase
{
    protected $powerUser;

    protected $normalUser;

    protected $access = [
        'roles' => [
            'admin',
        ],
        'permissions' => [
            'manage-roles',
            'search-users',
            'list-users',
            'update-users',
            'delete-users',
            'manage-admins-access',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        //Init power user
        $this->powerUser = factory(User::class)->create();
        $this->powerUser->assignRole($this->access['roles']);
        $this->powerUser->syncPermissions($this->access['permissions']);

        //Init normal user
        $this->normalUser = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function testAddPermissionFail()
    {   
        //Init third user
        $thirdUser = factory(User::class)->create();

        //Init permission
        $permission = factory(Permission::class)->create();

        //Add permission to third user
        $this->actingAs($thirdUser);

        $this->assertFalse($thirdUser->hasPermissionTo($permission));
    }
}
