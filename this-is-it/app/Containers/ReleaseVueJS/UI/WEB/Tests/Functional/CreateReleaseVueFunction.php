<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Tests\Functional;

use App\Containers\ReleaseVueJS\Tests\WebTestCase;
use App\Containers\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class CreateReleaseVueFunction.
 *
 * @group releasevuejs
 * @group web
 */
class CreateReleaseVueFunction extends WebTestCase
{

    // the endpoint to be called within this test (e.g., get@v1/users)
    protected $endpoint = '/releasevuejs/store';

    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles'       => '',
    ];

    /**
     * @test
     */
    public function testLoadShowCreateReleasePage_()
    {
        $user = User::find(1);
        $this->actingAs($user);
        // send the HTTP request
        $response = $this->get('/releasevuejs/new');

        // assert response status is correct
        $response->assertStatus(200);

        // assert we're hitting the correct route
        $response->assertViewIs('release::admin.admin-create-release-page');
    }

}
