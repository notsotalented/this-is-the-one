<?php

namespace App\Containers\User\Tests\Unit;

use App\Containers\User\Actions\FindUserByIdAction;
use App\Containers\User\Actions\GetAuthenticatedUserAction;
use App\Containers\User\Models\User;
use App\Containers\User\Tests\TestCase;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Transporters\DataTransporter;

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
            'id' => '14'
        ];
        $transporter = new DataTransporter($data);
        $action = \App::make(FindUserByIdAction::class);
        $user = $action->run($transporter);
    }

    public function testGetAuthUserNotFound()
    {
        $this->expectException(NotFoundException::class);

        $action = \App::make(GetAuthenticatedUserAction::class);
        $action->run();
    }

    public function testGetAuthUser()
    {
        (\Auth::attempt(['email' => 'admin@admin.com', 'password' => 'admin']));

        $action = \App::make(GetAuthenticatedUserAction::class);
        $user = $action->run();
        
        $this->assertEquals('1', $user->id);
    }
}
