<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('auth')->group(function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::prefix('settings')->group(function() {
        Route::prefix('users')->namespace('Settings')->group(function() {
            Route::get('/',                         'SettingsController@users')->name('settings.users');
            Route::get('/create',                   'SettingsController@usersCreate')->name('settings.users.create');
            Route::post('/store',                   'SettingsController@usersStore')->name('settings.users.store');
            Route::get('/edit/{id}',                'SettingsController@usersEdit')->name('settings.users.edit');
            Route::post('/update/{id}',             'SettingsController@usersUpdate')->name('settings.users.update');
            Route::get('/delete/{id}',              'SettingsController@usersDeleteConfirm')->name('settings.users.delete-confirm');
            Route::delete('/delete/{id}',           'SettingsController@usersDelete')->name('settings.users.delete');
            Route::get('/download-file/{id}',       'SettingsController@usersDownloadFile')->name('settings.users.download-file');
        });
    });

});
