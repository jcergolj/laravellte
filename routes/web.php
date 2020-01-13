<?php

Auth::routes(['register' => false]);

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home.index');

    Route::group(
        ['namespace' => 'Profile', 'prefix' => 'profile'],
        function () {
            Route::get('/', 'UserController@index')->name('profile.users.index');
            Route::post('/image', 'ImageController@update')->name('profile.images.update');
        }
    );

    Route::middleware('authorization:admin')->group(function () {
        Route::resource('users', 'UserController')->only(['index', 'create', 'edit']);

        Route::resource('roles', 'RoleController')->only(['index', 'create', 'edit']);
    });
});
