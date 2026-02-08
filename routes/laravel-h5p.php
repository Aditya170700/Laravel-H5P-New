<?php

Route::group(['middleware' => ['web']], function () {
    if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('h5p/library', "Aditya\LaravelH5P\Http\Controllers\LibraryController@index")->name('h5p.library.index');
            Route::get('h5p/library/show/{id}', "Aditya\LaravelH5P\Http\Controllers\LibraryController@show")->name('h5p.library.show');
            Route::post('h5p/library/store', "Aditya\LaravelH5P\Http\Controllers\LibraryController@store")->name('h5p.library.store');
            Route::delete('h5p/library/destroy', "Aditya\LaravelH5P\Http\Controllers\LibraryController@destroy")->name('h5p.library.destroy');
            Route::get('h5p/library/restrict', "Aditya\LaravelH5P\Http\Controllers\LibraryController@restrict")->name('h5p.library.restrict');
            Route::post('h5p/library/clear', "Aditya\LaravelH5P\Http\Controllers\LibraryController@clear")->name('h5p.library.clear');
        });

        Route::resource('h5p', "Aditya\LaravelH5P\Http\Controllers\H5pController");

        // ajax
        Route::match(['GET', 'POST'], 'ajax/libraries', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax', 'Aditya\LaravelH5P\Http\Controllers\AjaxController')->name('h5p.ajax');
        Route::get('ajax/libraries', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
        Route::match(['GET', 'POST'], 'ajax/content-type-cache', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
        Route::match(['GET', 'POST'], 'ajax/content-hub-metadata-cache', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@contentHubMetadataCache')->name('h5p.ajax.content-hub-metadata-cache');
        Route::post('ajax/library-install', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@files')->name('h5p.ajax.files');
        Route::get('ajax/finish', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@finish')->name('h5p.ajax.finish');
        Route::post('ajax/content-user-data', 'Aditya\LaravelH5P\Http\Controllers\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
    }

    // export
    //    if (config('laravel-h5p.use_router') == 'EXPORT' || config('laravel-h5p.use_router') == 'ALL') {
    Route::get('h5p/embed/{id}', 'Aditya\LaravelH5P\Http\Controllers\EmbedController')->name('h5p.embed');
    Route::get('h5p/export/{id}', 'Aditya\LaravelH5P\Http\Controllers\DownloadController')->name('h5p.export');
//    }
});
