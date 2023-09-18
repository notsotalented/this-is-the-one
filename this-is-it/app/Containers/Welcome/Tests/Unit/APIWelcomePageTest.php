<?php

namespace App\Containers\Welcome\Tests\Unit;

use App\Containers\User\Models\User;
use App\Containers\Welcome\Tests\TestCase;

/**
 * Class APIWelcomePageTest.
 *
 * @group welcome
 * @group unit
 */
class APIWelcomePageTest extends TestCase
{
    /**
     * @test
     */
    public function testApiWelcome()
    {
        //Access admin route welcome page
        $response = $this->call('GET', route('api_welcome_root_page'));
        $response->assertStatus(200);
        $response->assertSee('Welcome to Apiato');
        //Access admin route welcome page v1
        $response = $this->call('GET', route('v1_api_landing_route'));
        $response->assertStatus(200);
        $response->assertSee('Welcome to Apiato (API V1)');
    }

    public function testApiLoginLogoutAdmin() {
        //Assert no logged user
        $this->assertFalse(\Auth::check());
        //Access admin route login page
        $response = $this->call('GET', route('get_admin_login_page'));
        $response->assertStatus(200);
        $response->assertSee('Login');

        $data_false = [
            'email' => 'admin@admin.com',
            'password' => 'admin123',
        ];
        $data = [
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'remember_me' => false
        ];
        
        //Post Json data false
        $response = $this->call('POST', route('post_admin_login_form'), $data_false);
        $response->assertStatus(302);
        $response->assertRedirect(route('get_admin_login_page'));
        $response->assertSessionHas('status', 'Incorrect User Credentials');

        //Post Json data
        $response = $this->call('POST', route('post_admin_login_form'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('get_admin_dashboard_page'));
        //Assert logged user
        $this->assertTrue(\Auth::check());

        //Logout
        $response = $this->call('POST', route('post_admin_logout_form'));
        $response->assertStatus(302);
        $response->assertRedirect(route('get_admin_login_page'));
    }

    public function testApiAdminDashboard() {
        //Assert no logged user
        $this->assertFalse(\Auth::check());
        //Access admin dashboard page 401
        $response = $this->call('GET', route('get_admin_dashboard_page'));
        $response->assertStatus(401);

        //Admin login
        \Auth::login(User::find(1));
        //Access admin dashboard page
        $response = $this->call('GET', route('get_admin_dashboard_page'));
        $response->assertStatus(200);
        $response->assertSee('Welcome Admin');

        //Logout admin login s.o else
        \Auth::logout();
        $user = factory(User::class)->create();
        \Auth::login($user);

        //Access admin dashboard page 403
        $response = $this->call('GET', route('get_admin_dashboard_page'));
        $response->assertStatus(403);
    }

    public function testUserNotAdminLoginDashboard() {
        //Create user
        $user = factory(User::class)->create([
            'email' => 'user@user.com',
            'password' => bcrypt('user123'),
        ]);

        //Assert no logged user
        $this->assertFalse(\Auth::check());

        //Login with user
        $response = $this->call('POST', route('post_admin_login_form'), [
            'email' => 'user@user.com',
            'password' => 'user123',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('get_admin_login_page'));
        $response->assertSessionHas('status', 'This User does not have an Admin permission.');
    }
}
