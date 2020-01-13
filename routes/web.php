<?php

Auth::routes(['register' => false]);

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('accepted-invitations/create', 'AcceptedInvitationController@create')
    ->name('accepted-invitations.create');

Route::get('confirmed-emails/store', 'ConfirmedEmailController@store')
    ->name('confirmed-emails.store');

Route::middleware(['auth'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home.index');

    Route::group(
        ['namespace' => 'Profile', 'prefix' => 'profile'],
        function () {
            Route::get('/', 'UserController@index')->name('profile.users.index');
            Route::post('/image', 'ImageController@update')->name('profile.images.update');
        }
    );

    Route::middleware(['authorization'])->group(function () {
        Route::resource('users', 'UserController')->only(['index', 'create', 'edit']);

        Route::resource('roles', 'RoleController')->only(['index', 'create', 'edit']);
    });
});
