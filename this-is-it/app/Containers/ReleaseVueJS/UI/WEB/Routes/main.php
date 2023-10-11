<?php

Route::prefix('releasevuejs')->group(function () {

    Route::get('/new', [
        'as'         => 'web_releasevuejs_create',
        'uses'       => 'Controller@create',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::delete('/delete', [
        'as'         => 'web_releasevuejs_delete_bulk',
        'uses'       => 'Controller@deleteBulk',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::delete('/{id}/delete', [
        'as'         => 'web_releasevuejs_delete',
        'uses'       => 'Controller@delete',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::get('/{id}/edit', [
        'as'         => 'web_releasevuejs_edit',
        'uses'       => 'Controller@edit',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::get('/{id}', [
        'as'         => 'web_releasevuejs_show',
        'uses'       => 'Controller@show',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::get('/', [
        'as'         => 'web_releasevuejs_get_all_release',
        'uses'       => 'Controller@getAllRelease',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::get('/{id}', [
        'as'         => 'web_releasevuejs_show_detail_release',
        'uses'       => 'Controller@showDetailRelease',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::post('/store', [
        'as'         => 'web_releasevuejs_store',
        'uses'       => 'Controller@store',
        'middleware' => [
            'auth:web',
        ],
    ]);
    Route::put('/{id}', [
        'as'         => 'web_releasevuejs_update',
        'uses'       => 'Controller@update',
        'middleware' => [
            'auth:web',
        ],
    ]);
});