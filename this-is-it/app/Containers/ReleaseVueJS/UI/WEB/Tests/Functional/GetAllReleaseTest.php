<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Tests\Functional;

use App\Containers\ReleaseVueJS\Tests\WebTestCase;
use App\Containers\User\Models\User;
use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use Illuminate\Support\Facades\Storage;

/**
 * Class GetAllReleaseTest.
 *
 * @group releasevuejs
 * @group web
 */
class GetAllReleaseTest extends WebTestCase
{

    // the endpoint to be called within this test (e.g., get@v1/users)
    protected $endpoint = '/releasevuejs';

    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * @test
     */
    public function testGetAllReleaseWithAdminRole_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        // send the HTTP request
        $response = $this->get($this->endpoint);

        // assert the response status
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function testGetAllReleaseWithClientRole_()
    {
        Storage::fake('public');
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // send the HTTP request
        $response = $this->get($this->endpoint);

        // assert the response status
        $response->assertStatus(403);

        Storage::fake('public');
    }

    /**
     * @testGetOneRelease_
     */
    public function testGetOneRelease_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');
        $release = factory(ReleaseVueJS::class, 6)->create();
        // send the HTTP request
        $response = $this->get($this->endpoint . '/' . $release[0]->id);

        // assert the response status
        $response->assertStatus(200);
        Storage::fake('public');
    }

    /**
     * @testGetReleasesByAjax_
     */
    public function testGetReleasesByAjax_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        // send the HTTP request
        $response = $this->json('get', $this->endpoint, ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // assert the response status
        $response->assertStatus(200);
    }
}