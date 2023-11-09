<?php
Route::prefix('releasevuejs')->middleware(['auth:web'])->group(function () {
    Route::get('/new', [
        'as' => 'web_releasevuejs_create',
        'uses' => 'Controller@create'
    ]);
    Route::delete('/delete', [
        'as' => 'web_releasevuejs_delete_bulk',
        'uses' => 'Controller@deleteBulk'
    ]);
    Route::delete('/{id}/delete', [
        'as' => 'web_releasevuejs_delete',
        'uses' => 'Controller@delete'
    ]);
    Route::get('/{id}/edit', [
        'as' => 'web_releasevuejs_edit',
        'uses' => 'Controller@edit'
    ]);
    Route::get('/', [
        'as' => 'web_releasevuejs_get_all_release',
        'uses' => 'Controller@getAllRelease'
    ]);
    Route::get('/{id}', [
        'as' => 'web_releasevuejs_show_detail_release',
        'uses' => 'Controller@showDetailRelease'
    ]);
    Route::post('/store', [
        'as' => 'web_releasevuejs_store',
        'uses' => 'Controller@store'
    ]);
    Route::put('/{id}', [
        'as' => 'web_releasevuejs_update',
        'uses' => 'Controller@update'
    ]);
});

Route::get("/error/{code}", function ($code) {
    abort_if(
        in_array(request()->code, [401, 403, 404, 419, 422, 429, 500, 503]),
        $code
    ) ?? abort(404);
});
?>