<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Tests\Functional;

use App\Containers\ReleaseVueJS\Models\ReleaseVueJS;
use App\Containers\ReleaseVueJS\Tests\WebTestCase;
use App\Containers\User\Models\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class EditReleaseTest.
 *
 * @group releasevuejs
 * @group web
 */
class EditReleaseTest extends WebTestCase
{
    // use RefreshDatabase;


    // the endpoint to be called within this test (/releases/{id})
    protected $endpoint = '/releasevuejs/{id}';

    // fake some access rights
    protected $access = [
        'permissions' => '',
        'roles' => 'admin',
    ];

    /**
     * @test
     */
    public function testLoadEditReleasePage_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        // send the HTTP request
        $response = $this->injectId($release[0]->id)->get($this->endpoint . '/edit');

        // assert response status is correct
        $response->assertStatus(200);

        // assert we are getting the correct view template
        $response->assertViewIs('releasevuejs::admin.admin-create-release-page');
    }

    /**
     * @test
     */
    public function testLoadEditReleasePageFail_()
    {
        $user = User::find(1);
        $this->actingAs($user);

        // send the HTTP request
        $response = $this->injectId(999)->get($this->endpoint . '/edit');

        // dd($response);
        // assert response status is correct
        $response->assertStatus(302);
    }

    /** 
     * Required update data validation provider.
     * 
     * @codeCoverageIgnore
     *
     * @return \string[][]
     */
    public function requiredUpdateDataProvider(): array
    {
        return [
            'Update Release With Valid Data have not old images' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ],
                    'images_old' => [],
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release With Valid Data have old images' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ],
                    'images_old' => [
                        '/storage/images-release/1release.jpg',
                    ],
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release With Null Data' => [
                [
                    'name' => '',
                    'title_description' => '',
                    'detail_description' => '',
                    'is_publish' => '',
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'name',
                        'title_description',
                        'detail_description',
                        'is_publish',
                    ]
                ],
            ],
            'Update Release Without Field' => [
                [],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'title_description',
                        'detail_description',
                    ]
                ],
            ],
            'Update Release With Null name' => [
                [
                    'name' => '',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'name',
                    ]
                ],
            ],
            'Update Release With Null title_description' => [
                [
                    'name' => 'update',
                    'title_description' => '',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'title_description',
                    ]
                ],
            ],
            'Update Release With Null detail_description' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => '',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'detail_description',
                    ]
                ],
            ],
            'Update Release With Null is_publish' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => '',
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'is_publish'
                    ]
                ],
            ],
            'Update Release With Invalid is_publish' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 'test',
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'is_publish',
                    ]
                ],
            ],
            'Update Release With Null images' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => []
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release Without Field name' => [
                [
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release Without Field title_description' => [
                [
                    'name' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'title_description',
                    ],
                ],
            ],
            'Update Release Without Field detail_description' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'detail_description',
                    ],
                ],
            ],
            'Update Release Without Field is_publish' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'images' => [
                        UploadedFile::fake()->image('update.jpg', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release Without Field images' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ],
            'Update Release With Invalid images' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        'test',
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'images.0'
                    ],
                ],
            ],
            'Update Release With Invalid images type' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => 'update',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->create('test.pdf', 100, 100),
                    ]
                ],
                'assert' => [
                    'status' => 302,
                    'session' => 'errors',
                    'fieldHasError' => [
                        'images.0'
                    ],
                ],
            ],
            'Update release with image from quill' => [
                [
                    'name' => 'update',
                    'title_description' => 'update',
                    'detail_description' => '<p><img src="image_0"></p><p><img src="image_1"></p><p><img src="image_2"></p>',
                    'is_publish' => 1,
                    'images' => [
                        UploadedFile::fake()->image('release.jpg', 100, 100),
                        UploadedFile::fake()->image('release.png', 100, 100),
                        UploadedFile::fake()->image('release.webp', 100, 100)
                    ],
                    'images_from_quill' => [
                        UploadedFile::fake()->image('release.jpg', 100, 100),
                        UploadedFile::fake()->image('release.png', 100, 100),
                        UploadedFile::fake()->image('release.webp', 100, 100)
                    ]
                ],

                'assert' => [
                    'status' => 302,
                    'route' => '/releasevuejs/new',
                    'session' => 'success',
                    'fieldHasError' => [
                    ],
                ],
            ]

        ];
    }

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
     * @test
     * 
     * @dataProvider requiredUpdateDataProvider
     */
    public function testUpdateRelease($data, $assert)
    {
        $user = User::find(1);

        $this->actingAs($user);

        Storage::fake('public');

        $release = factory(ReleaseVueJS::class, 6)->create();

        $this->from('releasevuejs/' . $release[0]->id . '/edit');

        $data = array_merge($data, [
            'id' => $release[0]->id
        ]);

        // send the HTTP request
        $response = $this->injectId($release[0]->id)->put($this->endpoint, $data);

        // assert response status is redirection
        $response->assertStatus($assert['status']);

        $url = 'releasevuejs' . '/' . $data['id'] . '/edit';

        // assert the direct to the correct route
        $response->assertRedirect($url);

        if ($assert['session'] == 'success') {
            // assert the response body contains the correct string
            $response->assertSessionHas($assert['session']);

            // assert the data was stored in the database
            $this->assertDatabaseHas('releasevuejs', [
                'id' => $data['id'],
                'name' => $data['name'] ?? $release[0]->name,
                'title_description' => $data['title_description'],
            ]);

        } else {
            // assert the response body contains the correct string
            $response->assertSessionHasErrors($assert['fieldHasError']);

            // assert the data was stored in the database
            $this->assertDatabaseMissing('releasevuejs', [
                'id' => $data['id'],
                'name' => $data['name'] ?? '',
                'title_description' => $data['title_description'] ?? '',
            ]);
        }

        Storage::fake('public');
    }
}