<?php

use App\Containers\Authentication\Actions\WebLoginAction;
use App\Containers\Authentication\Tests\TestCase;
use App\Containers\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Containers\Authorization\Models\Permission;
use App\Containers\Authorization\Models\Role;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;

/**
 * Class DashboardPageTest.
 *
 * @group welcome
 * @group unit
 */
class DashboardPageTest extends TestCase
{

    protected $testingUser;
    protected $testingUserWithoutAccess;

    protected $access = [
        'roles' => 'admin',
        'permissions' => ['manage-roles', 'access-dashboard', 'list-users', 'search-users'],
    ];

    protected $guard_name = 'web';

    protected $endpointDashboard  = '/dashboard';
    protected $endpointLogin  = '/login';

    /**
     * Set up the test environment before each test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        //User setup
        $this->testingUser = factory(User::class)->create();
        $this->testingUser->assignRole($this->access['roles']);
        $this->testingUser->syncPermissions($this->access['permissions']);

        //Another user setup
        $this->testingUserWithoutAccess = factory(User::class)->create();
    }

    /**
     * Test the dashboard access no login
     *
     * @throws \Exception if the dashboard access fails.
     */
    public function testDashboardAccessNoLogin()
    {
        $response = $this->get($this->endpointDashboard);

        $response->assertStatus(401);
        
    }

    /**
     * Test the ability to access the dashboard.
     *
     * @throws \Exception if the dashboard access fails.
     */
    public function testDashboardAccess()
    {
        $response = $this->actingAs($this->testingUserWithoutAccess)->get($this->endpointDashboard);
        $response->assertStatus(403);
    }


    /**
     * A test case to check dashboard access after login.
     *
     * @return void
     */
    public function test_dashboard_access_with_login_main() 
    {
        //Access main dashboard
        $response = $this->actingAs($this->testingUser)->get($this->endpointDashboard . "?table=2");
        $response->assertStatus(200);
    }
}
