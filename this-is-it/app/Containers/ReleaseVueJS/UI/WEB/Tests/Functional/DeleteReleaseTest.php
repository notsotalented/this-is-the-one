<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Tests\Functional;

use App\Containers\ReleaseVueJS\Tests\WebTestCase;
use App\Containers\User\Models\User;
use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;

use Illuminate\Support\Facades\Storage;

/**
 * Class DeleteReleaseTest.
 *
 * @group releasevuejs
 * @group web
 */
class DeleteReleaseTest extends WebTestCase
{
    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles' => 'admin',
    ];

    /**
     * @from
     *
     * @param string $url
     *
     * @return $this
     */
    public function from(string $url)
    {
        $this->app['session']->setPreviousUrl($url);
        return $this;
    }

    /**
     * @testDeleteReleaseSuccess_
     */
    public function testDeleteReleaseSuccess_()
    {
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => $release[0]->id,
            'name' => $release[0]->name,
        ];

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->delete(route('web_releasevuejs_delete', $release[0]->id));

        // assert the response status
        $response->assertStatus(302);

        // assert the redirect url
        $response->assertRedirect('/releasevuejs');

        // assert the data was deleted from the database
        $this->assertDatabaseMissing('releasevuejs', ['id' => $release[0]->id]);

        // assert a session flash message was set
        $response->assertSessionHas('success', '<p style="color:blue">Release <strong>' . $data['name'] . '</strong> Deleted Successfully</p>');
    }

    /**
     * @testDeleteReleaseFail_
     */
    public function testDeleteReleaseFail_()
    {
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->delete(route('web_releasevuejs_delete', 999));

        // assert the response status
        $response->assertStatus(302);

        // assert the redirect url
        $response->assertRedirect('/releasevuejs');

        // assert the data was deleted from the database
        $this->assertDatabaseHas('releasevuejs', ['id' => $release[0]->id]);

        // assert a session flash message was set
        $response->assertSessionHas('error', '<p style="color:red"> Release Not Found </p>');
    }

    /**
     * @testDeleteReleaseByAjaxSuccess_
     */
    public function testDeleteReleaseByAjaxSuccess_()
    {
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => $release[0]->id,
            'name' => $release[0]->name,
        ];

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->json('delete', route('web_releasevuejs_delete', $release[0]->id), [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // assert the response status
        $response->assertStatus(200);

        // assert the data was deleted from the database
        $this->assertDatabaseMissing('releasevuejs', ['id' => $release[0]->id]);

        // assert a session flash message was set
        $response->assertJson(['success' => '<p style="color:blue">Release <strong>' . $data['name'] . '</strong> Deleted Successfully</p>']);
    }

    /**
     * @testDeleteReleaseByAjaxFail_
     */
    public function testDeleteReleaseByAjaxFail_()
    {
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->json('delete', route('web_releasevuejs_delete', 999), [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // assert the response status
        $response->assertStatus(200);

        // assert the data was deleted from the database
        $this->assertDatabaseHas('releasevuejs', ['id' => $release[0]->id]);

        // assert a session flash message was set
        $response->assertJson(['error' => '<p style="color:red"> Release Not Found </p>']);
    }

    /**
     * testDeleteReleaseWithoutLogin_
     */
    public function testDeleteReleaseWithoutLogin_()
    {
        Storage::fake('public');
        $release = factory(ReleaseVueJS::class, 6)->create();

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->delete(route('web_releasevuejs_delete', $release[0]->id));

        // assert the response status
        $response->assertStatus(401);
    }

    /**
     * testDeleteBulkReleaseSuccess_
     */
    public function testDeleteBulkReleaseSuccess_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');
        $release = factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => [$release[0]->id, $release[1]->id, $release[2]->id],
            'name' => $release[0]->name . ', ' . $release[1]->name . ', ' . $release[2]->name,
        ];

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->delete(route('web_releasevuejs_delete_bulk'), $data);

        // assert the response status
        $response->assertStatus(302);

        // assert the redirect url
        $response->assertRedirect('/releasevuejs');

        // assert the data was deleted from the database
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][0]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][1]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][2]]);

        // assert a session flash message was set
        $response->assertSessionHas('success', '<p style="color:blue"> Release <strong>' . $data['name'] . '</strong> Deleted Successfully </p>');
    }

    /**
     * testDeleteBulkReleaseFail_
     */
    public function testDeleteBulkReleaseFail_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');
        factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => [999, 888, 777],
            'name' => '999, 888, 777',
        ];

        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][0]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][1]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][2]]);

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->delete(route('web_releasevuejs_delete_bulk'), $data);

        // assert the response status
        $response->assertStatus(302);

        // assert the redirect url
        $response->assertRedirect('/releasevuejs');
    }

    /**
     * testDeleteBulkReleaseByAjax_
     */
    public function testDeleteBulkReleaseByAjax_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');
        $release = factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => [$release[0]->id, $release[1]->id, $release[2]->id],
            'name' => $release[0]->name . ', ' . $release[1]->name . ', ' . $release[2]->name,
        ];

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->json('delete', route('web_releasevuejs_delete_bulk'), $data, ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // assert the response status
        $response->assertStatus(200);

        // assert the data was deleted from the database
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][0]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][1]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][2]]);

        // assert a session flash message was set
        $response->assertJson(['success' => '<p style="color:blue"> Release <strong>' . $data['name'] . '</strong> Deleted Successfully </p>']);
    }

    /**
     * testDeleteBulkReleaseByAjaxFail_
     */
    public function testDeleteBulkReleaseByAjaxFail_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');
        factory(ReleaseVueJS::class, 6)->create();

        $data = [
            'id' => [999, 888, 777],
            'name' => '999, 888, 777',
        ];

        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][0]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][1]]);
        $this->assertDatabaseMissing('releasevuejs', ['id' => $data['id'][2]]);

        $this->from('/releasevuejs');

        // send the HTTP request
        $response = $this->json('delete', route('web_releasevuejs_delete_bulk'), $data, ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // assert the response status
        $response->assertStatus(422);

        // assert the redirect url
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'id.0' => ['The selected id.0 is invalid.'],
                'id.1' => ['The selected id.1 is invalid.'],
                'id.2' => ['The selected id.2 is invalid.'],
            ]
        ]);
    }
}