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

    Route::prefix('transactions')->namespace('Transactions')->group(function() {
        Route::get('/',                         'TransactionsController@index')->name('transactions');
        Route::get('/create',                   'TransactionsController@create')->name('transactions.create');
        Route::post('/store',                   'TransactionsController@store')->name('transactions.store');
    });

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

    Route::get('/give-money', function() {
        $bills = \App\Models\Bill::all();
        $bills->each(function($el) {
            $transaction = new \App\Models\Transaction();
            $transaction->type_id = 2;
            $transaction->target_bill_id = $el->id;
            $transaction->amount = 500;
            $transaction->save();
        });
        return redirect()->back();
    });
});
