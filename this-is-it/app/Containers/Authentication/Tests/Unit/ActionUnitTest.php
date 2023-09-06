<?php

namespace App\Containers\Authentication\Tests\Unit;

use App\Containers\Authentication\Actions\WebAdminLoginAction;
use App\Containers\Authentication\Tests\TestCase;
use App\Containers\User\Models\User;

/**
 * Class ActionUnitTest.
 *
 * @group authentication
 * @group unit
 */
class ActionUnitTest extends TestCase
{
    protected $testUser;

    protected $guard_name = 'web';

    protected $access = [
        'roles' => 'admin',
        'permissions' => ['manage-roles', 'access-dashboard', 'list-users', 'search-users'],
    ];
    
    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = factory(User::class)->create();
        $this->testUser->assignRole($this->access['roles']);
        $this->testUser->syncPermissions($this->access['permissions']);
        $this->testUser->confirmed = 1;

    }


    public function testAdminLogin()
    {
        $request = new \Request;

        $request->merge([
            'search_bar' => '1',
        ]);
        $response = $this->actingAs($this->testUser)->get('/search');

        dd($response);
    }
}
