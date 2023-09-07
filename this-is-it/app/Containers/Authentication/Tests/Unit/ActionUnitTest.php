<?php

namespace App\Containers\Authentication\Tests\Unit;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\Authentication\Actions\WebAdminLoginAction;
use App\Containers\Authentication\Tests\TestCase;
use App\Containers\User\Models\User;
use Illuminate\Support\Facades\Request;

/**
 * Class ActionUnitTest.
 *
 * @group authentication
 * @group unit
 */
class ActionUnitTest extends TestCase
{
    protected $testUser;
    protected $testUserWithoutAccess;

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

        $this->testUserWithoutAccess = factory(User::class)->create();
    }

    /**
     * Test the live search functionality.
     *
     * @return void
     */
    public function testLiveSearch()
    {
        //Setup
        $byID = '<li><a class="dropdown-item disabled">By ID:</a></li>';
        $byName = '<a class="dropdown-item disabled">By Name:</a></li>';
        $byEmail = '<a class="dropdown-item disabled">By Email:</a></li>';
        $divideName = '<li class="dropdown-divider">Name</li>';
        $nothing = '<li><a class="dropdown-item disabled">Not found anything!</a></li>';
        $notAjax = '<li><a class="dropdown-item disabled">Not Ajax!</a></li>';
        $andMore = '<li><a class="dropdown-item disabled">And more...</a></li>';

        //Perform ajax search: By ID
        $result = $this->actingAs($this->testUser)
                       ->json('get', '/search', 
                       ['search_bar' => $this->testUser->id, "_method" => "GET"], 
                       ['X-Requested-With' => 'XMLHttpRequest']);
        //Assert response
        $result->assertStatus(200);
        $result->assertJson([
            '0' => $byID,
            '1' => '<li><a class="dropdown-item" href="/users/' .$this->testUser->id. '">' . $this->testUser->id . '</a></li>',
            '2' => $divideName,
        ]);

        //Add users
        $alibaba = factory(User::class)->create([
            'name' => 'Alibaba',
            'email' => 'Alibaba@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $alibabon = factory(User::class)->create([
            'name' => 'Alibabon',
            'email' => 'Alibabon@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $alibanam = factory(User::class)->create([
            'name' => 'Alibanam',
            'email' => 'Alibanam@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        //Perform ajax search: By Name
        $result = $this->actingAs($this->testUser)
                       ->json('get', '/search',
                       ['search_bar' => 'Ali', "_method" => "GET"],
                       ['X-Requested-With' => 'XMLHttpRequest']);
        //Assert response
        $result->assertStatus(200);
        $result->assertJson([
            '0' => $byName,
            '1' => '<li><a class="dropdown-item" href="/users/' .$alibaba->id. '">' . $alibaba->name . '</a></li>',
            '2' => '<li><a class="dropdown-item" href="/users/' .$alibabon->id. '">' . $alibabon->name . '</a></li>',
            '3' => '<li><a class="dropdown-item" href="/users/' .$alibanam->id. '">' . $alibanam->name . '</a></li>',
            '4' => $divideName,
            '5' => $byEmail,
            '6' => '<li><a class="dropdown-item" href="/users/' .$alibaba->id. '">' . $alibaba->email . '</a></li>',
            '7' => '<li><a class="dropdown-item" href="/users/' .$alibabon->id. '">' . $alibabon->email . '</a></li>',
            '8' => '<li><a class="dropdown-item" href="/users/' .$alibanam->id. '">' . $alibanam->email . '</a></li>',
        ]);

        //Perform ajax search: By nothing
        $result = $this->actingAs($this->testUser)
                       ->json('get', '/search',
                       ['search_bar' => 'kekekekeke32746238', "_method" => "GET"],
                       ['X-Requested-With' => 'XMLHttpRequest']);
        //Assert response
        $result->assertStatus(200);
        $result->assertJson([
            '0' => $nothing, 
        ]);

        //Perform non ajax search
        $result = $this->actingAs($this->testUser)
                       ->get('/search',
                       ['search_bar' => 'kekekekeke', "_method" => "GET"]);
        //Assert response
        $result->assertStatus(200);
        $result->assertJson([
            '0' => $notAjax,
        ]);

        //Add users
        $alibasau = factory(User::class)->create([
            'name' => 'Alibasau',
            'email' => 'Alibasau@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $alibabay = factory(User::class)->create([
            'name' => 'Alibabay',
            'email' => 'Alibabay@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $alibatam = factory(User::class)->create([
            'name' => 'Alibatam',
            'email' => 'Alibatam@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $alibachin = factory(User::class)->create([
            'name' => 'Alibachin',
            'email' => 'Alibachin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        //Perform ajax search with more than 5 entries return
        $result = $this->actingAs($this->testUser)
                       ->json('get', '/search',
                       ['search_bar' => 'Ali', "_method" => "GET"],
                       ['X-Requested-With' => 'XMLHttpRequest']);
        //Assert response
        $result->assertStatus(200);
        $result->assertJsonFragment([
            '<li><a class="dropdown-item active" style="font-weight: bold; font-style: italic" href="/users?search=Ali">Show all results!</a></li>',
        ]);
    }

    public function testShowWelcomePage()
    {
        $response = $this->actingAs($this->testUser)->get('/');
        $response->assertStatus(200);
    }

    public function testShowHomePage() {
        $response = $this->actingAs($this->testUser)->get('/home');
        $response->assertStatus(200);
    }

    public function testShowTestPage()
    {
        $response = $this->actingAs($this->testUser)->get('/test-page');
        $response->assertStatus(200);
    }
}