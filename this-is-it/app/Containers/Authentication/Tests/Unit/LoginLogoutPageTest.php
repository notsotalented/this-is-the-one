<?php

namespace App\Containers\Authentication\Tests\Unit;

use App\Containers\Authentication\Actions\WebLoginAction;
use App\Containers\Authentication\Tests\TestCase;
use App\Containers\User\Models\User;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\App;


/**
 * Class LoginLogoutPage.
 *
 * @group authentication
 * @group unit
 */
class LoginLogoutPageTest extends TestCase
{
    protected $admin_user;
    protected $normie_user;
    //protected $auth = false;
    protected $data_good = [
        'email' => 'admin@admin.com',
        'password' => 'admin',
    ];
    protected $data_bad = [
        'email' => 'admin@admin.com',
        'password' => '12345'
    ];
    protected $data_too_long = [
        'email' => 'admin@admin4325t2834ytrejhrvtfbdnergfhdnjerhgfdjewhrghbdjehbdherbfhrgfhgfvhfb.com',
        'password' => '123432389023809èuiuiowewewqefbdjkdhsakjdhsakjdhaksdhkjasdhskjdhbwmebqwebwqnewqe45'
    ];
    protected $data_too_short_and_not_email = [
        'email' => 'ad345uioerhgv@',
        'password' => '1',
    ];

    protected $endpoint_login = '/login';
    protected $endpoint_logout = '/logout';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_login_page_access_without_login()
    {
        $response = $this->get($this->endpoint_login);

        $response->assertStatus(200);
    }

    public function test_login_page_access_with_login()
    {
        $this->admin_user = $this->getTestingUser();
        $response = $this->actingAs($this->admin_user)->get($this->endpoint_login);

        $response->assertStatus(302);
        $response->assertRedirect('/');

    }

    public function test_login_success()
    {
       $transporter = new DataTransporter ($this->data_good);

       $action = App::make(WebLoginAction::class);

       $user = $action->run($transporter);

       $this->assertInstanceOf(User::class, $user);

       $this->assertEquals($user->email, $this->data_good['email']);
    }

    public function test_login_fail_incorrect_credentials()
    {
        $response = $this->post($this->endpoint_login, $this->data_bad);

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $response->assertSessionHas('status', 'Incorrect User Credentials');
    }

    public function test_login_fail_not_exist()
    {
        $response = $this->postJson('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);
    
        $response->assertStatus(422);
    
        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                'email' => ['The email you entered does not exist.'],
            ],
        ]);
    }

    public function test_login_fail_too_long()
    {
        $response = $this->postJson('/login', $this->data_too_long);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'email' => ['The email may not be greater than 40 characters.'],
                'password' => ['The password may not be greater than 30 characters.'],
            ],
        ]);
    }

    public function test_login_fail_too_short_and_not_email()
    {
        $response = $this->postJson('/login', $this->data_too_short_and_not_email);

        $response->assertStatus(422);

        $response->assertJson([
            'errors' => [
                'email' => ['The email must be a valid email address.'],
                'password' => ['The password must be at least 5 characters.'],
            ],
        ]);
    }

    public function test_logout_fail()
    {
        $this->auth = false;

        $response = $this->get($this->endpoint_logout);

        $response->assertStatus(401);
        
    }

    public function test_logout_success()
    {
        $transporter = new DataTransporter ($this->data_good);
        $action = App::make(WebLoginAction::class);
        $user = $action->run($transporter);

        $response = $this->actingAs($user)->get($this->endpoint_logout);

        $response->assertStatus(302);

        $response->assertRedirect('/');
    }

    public function testLoginLogoutWebPage() {
        $data = [
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'remember_me' => false
        ];
        $data_false = [
            'email' => 'admin@admin.com',
            'password' => 'admin123',
        ];
        
        //Post Json data false
        $response = $this->call('POST', 'login', $data_false);
        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertSessionHas('status', 'Incorrect User Credentials');

        //Post Json data
        $response = $this->call('POST', 'login', $data);
        $response->assertStatus(302);
        $response->assertRedirect('');
        $response->assertSessionHas('status', 'Login successfully');
    }
}
