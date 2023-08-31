<?php

namespace App\Containers\User\Tests\Unit;

use App\Containers\User\Models\User;
use App\Containers\User\Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class UserProfileTest.
 *
 * @group user
 * @group unit
 */
class UserProfileTest extends TestCase
{
    protected $endpoint = '/users';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testAccessUsers()
    {
        //No login
        $response = $this->get($this->endpoint);
        $response->assertStatus(401);

        //Login no permissions
        $tester = factory(User::class)->create();

        $response = $this->actingAs($tester)->get($this->endpoint);
        $response->assertStatus(403);

        //Login no permissions to oneself profile
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester->id);
        $response->assertStatus(200);

        //Login no permissions to someone else profile
        $tester2 = factory(User::class)->create();

        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id);
        $response->assertStatus(403);

        //Login with permissions
        $tester = factory(User::class)->create();
        $tester->syncPermissions('list-users', 'search-users');
        
        $response = $this->actingAs($tester)->get($this->endpoint);
        $response->assertStatus(200);

        //Login with permissions to oneself profile
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester->id);
        $response->assertStatus(200);

        //Login with permissions to someone else profile
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id);
        $response->assertStatus(200);
    }

    public function testUpdateUserFail() {
        $tester = factory(User::class)->create();
        $tester->syncPermissions('search-users');

        $tester2 = factory(User::class)->create();

        //Without permissions
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id . '/update');
        $response->assertStatus(403);

        //Set permissions
        $tester->syncPermissions('list-users', 'update-users');

        //With permissions, <= role level
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id . '/update');
        $response->assertStatus(403);
    }

    public function testUpdateUserAccess() {
        //Init tester
        $tester = factory(User::class)->create();
        //Set level via set role
        $tester->assignRole(Role::findByName('admin'));
        $tester->syncPermissions('search-users','list-users', 'update-users');

        //create a role
        $role_user = factory(Role::class)->create([
            'name' => 'user',
            'level' => 0,
            'description' => 'User'
        ]);
        
        //Init tester2
        $tester2 = factory(User::class)->create();
        //Set level via set role
        $tester2->assignRole('user');

        //Access
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id . '/update');
        $response->assertStatus(200);
    }

    public function testUpdateUser() {
        //Init tester
        $tester = factory(User::class)->create();
        //Set level via set role
        $tester->assignRole(Role::findByName('admin'));
        $tester->syncPermissions('search-users','list-users', 'update-users');

        //Test access
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester->id . '/update');
        $response->assertStatus(200);

        //Test update method
        $response = $this->actingAs($tester)->putJson($this->endpoint . '/' . $tester->id . '/update', [
            'email' => '',
            'id' => $tester->id,
            'name' => 'alibabababababa',
            'gender' => 'male',
            'birth' => '2000-01-01',
            'password_new' => '123456',
            'password_new2' => '123456',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect($this->endpoint . '/' . $tester->id . '/update');

        //Check updated information
        $this->assertDatabaseHas('users', [
            'id' => $tester->id,
            'email' => $tester->email,
            'name' => 'alibabababababa',
            'gender' => 'male',
            'birth' => '2000-01-01',
        ]);
    }

    public function testUpdateUserFailValidation() {
        //Init tester
        $tester = factory(User::class)->create();
        //Set level via set role
        $tester->assignRole(Role::findByName('admin'));
        $tester->syncPermissions('search-users','list-users', 'update-users');

        //Test update method
        $response = $this->actingAs($tester)->putJson($this->endpoint . '/' . $tester->id . '/update', [
            'email' => 'alibabon@',
            'id' => $tester->id,
            'name' => 'a43y2uwjdfhgtuh4jednfbgthrujdmfngbhtjrkfmnvbghtjurkdmvnbghtjrkdmcvnbghjfkcmvnbghjfkcmvnbghjfmvnbhgjt',
            'gender' => 'male',
            'birth' => '2000-01-01',
            'password_new' => '1234',
            'password_new2' => '1234',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email',
            'name',
            'password_new',
        ]);
    }

    public function testUpdateOwnProfileNoPermission() {
        //Init tester
        $tester = factory(User::class)->create();

        //Test access
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester->id . '/update');
        $response->assertStatus(200);

        //Test update method
        $response = $this->actingAs($tester)->putJson($this->endpoint . '/' . $tester->id . '/update', [
            'email' => 'toilaibaba@gmail.com',
            'id' => $tester->id,
            'name' => 'alibababababababonnam',
            'gender' => 'female',
            'birth' => '2000-02-09',
            'password_new' => '123456',
            'password_new2' => '123456', 
        ]);
        
        $response->assertStatus(302);
        $response->assertRedirect($this->endpoint . '/' . $tester->id . '/update');

        //Check updated information
        $this->assertDatabaseHas('users', [
            'id' => $tester->id,
            'email' => 'toilaibaba@gmail.com',
            'name' => 'alibababababababonnam',
            'gender' => 'female',
            'birth' => '2000-02-09',
        ]);
    }

    public function testUpdateOtherProfile() {
        //Init tester
        $tester = factory(User::class)->create();
        //Set level via set role
        $tester->assignRole('admin');
        $tester->syncPermissions('search-users','list-users', 'update-users', 'manage-roles');    

        //Create a role
        $role_user = factory(Role::class)->create([
            'name' => 'user',
            'level' => 0,
            'description' => 'User'
        ]);

        //Init tester2
        $tester2 = factory(User::class)->create();
        //Set level via set role
        $tester2->assignRole('user');

        //Test access
        $response = $this->actingAs($tester)->get($this->endpoint . '/' . $tester2->id . '/update');
        $response->assertStatus(200);

        //Test update method
        $response = $this->actingAs($tester)->putJson($this->endpoint . '/' . $tester2->id . '/power-update', [
            'email' => 'toilaibaba@gmail.com',
            'id' => $tester2->id,
            'name' => 'alibababababababonnam',
            'gender' => 'female',
            'birth' => '2000-02-10',
            'password_new' => '123456',
            'password_new2' => '123456',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect($this->endpoint . '/' . $tester2->id . '/update');

        //Check updated information
        $this->assertDatabaseHas('users', [
            'id' => $tester2->id,
            'email' => 'toilaibaba@gmail.com',
            'name' => 'alibababababababonnam',
            'gender' => 'female',
            'birth' => '2000-02-10',
        ]);
    }
}
