<?php

namespace App\Containers\User\Tests\Unit;

use App\Containers\Authorization\Actions\CreateRoleAction;
use App\Containers\Authorization\Actions\FindRoleAction;
use App\Containers\User\Actions\FindUserByIdAction;
use App\Containers\User\Actions\GetAllUsersAction;
use App\Containers\User\Actions\GetAuthenticatedUserAction;
use App\Containers\User\Models\User;
use App\Containers\User\Tasks\CountRegisteredUsersTask;
use App\Containers\User\Tasks\CountUsersTask;
use App\Containers\User\Tasks\DeleteUserTask;
use App\Containers\User\Tasks\FindUserByEmailTask;
use App\Containers\User\Tasks\GetAllUsersTask;
use App\Containers\User\Tests\TestCase;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Transporters\DataTransporter;
use Spatie\Permission\Models\Role;

/**
 * Class UserActionUnitTest.
 *
 * @group user
 * @group unit
 */
class UserActionUnitTest extends TestCase
{
    protected $testUser;

    protected $testUserWithNoAccess;

    protected $access = [
        'role' => 'admin',
        'permission' => 'manage-users',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = factory(User::class)->create();

        $this->testUserWithNoAccess = factory(User::class)->create();
    }

    /**
     * Test case for the testFindIDNotFound function.
     *
     * @throws NotFoundException When the ID is not found.
     * @return void
     */
    public function testFindIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $data = [
            'id' => '925'
        ];
        $transporter = new DataTransporter($data);
        $action = \App::make(FindUserByIdAction::class);
        $user = $action->run($transporter);
    }

    /**
     * Test case for the testGetAuthUserNotFound method.
     *
     * @throws NotFoundException when the authenticated user is not found
     */
    public function testGetAuthUserNotFound()
    {
        $this->expectException(NotFoundException::class);

        $action = \App::make(GetAuthenticatedUserAction::class);
        $action->run();
    }

    /**
     * Test the functionality of the testGetAuthUser function.
     *
     * @return void
     */
    public function testGetAuthUser()
    {
        (\Auth::attempt(['email' => 'admin@admin.com', 'password' => 'admin']));

        $action = \App::make(GetAuthenticatedUserAction::class);
        $user = $action->run();
        
        $this->assertEquals('1', $user->id);
    }

    /**
     * Test the countRegisteredUsersTask function.
     *
     * 
     * @return void
     */
    public function testCountRegisteredUsersTask()
    {
        factory(User::class, 10)->create();
        factory(User::class, 5)->create([
            'email' => null,
        ]);
        factory(User::class)->create([
            'email' => '',
        ]);

        $userNumbers = \App::make(CountRegisteredUsersTask::class)->run();
        
        $countDB = User::count();
        $userFromDB = User::all();
        foreach ($userFromDB as $user) {
            if ($user->email === null) {
                $countDB--;
            }
        }

        $this->assertEquals($countDB, $userNumbers);
    }

    /**
     * Test the countUsersTask function.
     *
     * @return void
     */
    public function testCountUsersTask()
    {
        factory(User::class, 10)->create();
        $userNumbers = \App::make(CountUsersTask::class)->run();

        $this->assertEquals(User::count(), $userNumbers);
    }

    /**
     * Tests the FindUserByEmailTask function.
     *
     * @return void
     */
    public function testFindUserByEmailTask() {
        factory(User::class)->create([
            'email' => 'aladin@admin.com',
        ]);

        $user = \App::make(FindUserByEmailTask::class)->run('aladin@admin.com');

        $this->assertNotNull($user);
    }

    public function testFindUserByEmailTaskFail() {
        $this->expectException(NotFoundException::class);

        \App::make(FindUserByEmailTask::class)->run('aladanh@admin.com');
    }

    public function testDeleteUserTaskFail() {
        $this->expectException(DeleteResourceFailedException::class);

        $user = factory(User::class)->create();
        $user_clone = $user;

        \App::make(DeleteUserTask::class)->run($user);
        \App::make(DeleteUserTask::class)->run($user_clone);
    }

    /**
     * Test the functionality of the getAllUsersTaskWithRole method.
     *
     * 
     * @return void
     */
    public function testGetAllUsersTaskWithRole() {
        $admins =  \App::make(GetAllUsersTask::class)->withRole('admin')->run();

        $this->assertCount(1, $admins);

        $this->testUser->assignRole('admin');

        $admins =  \App::make(GetAllUsersTask::class)->withRole('admin')->run();

        $this->assertCount(2, $admins);

        $data = new DataTransporter([
            'name' => 'client',
            'guard_name' => 'web',
            'level' => 0,
        ]);
        $role = \App::make(CreateRoleAction::class)->run($data);

        $this->testUserWithNoAccess->assignRole('client');

        $clients =  \App::make(GetAllUsersTask::class)->withRole('client')->run();
        $admins =  \App::make(GetAllUsersTask::class)->withRole('admin')->run();

        $this->assertCount(1, $clients);
        $this->assertCount(2, $admins);
    }
}
