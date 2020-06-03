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

Auth::routes([
    'register' => false,
    'reset' => false
]);

Route::middleware('auth')->group(function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::prefix('transactions')->namespace('Transactions')->group(function() {
        Route::get('/',                         'TransactionsController@index')->name('transactions');
        Route::get('/create',                   'TransactionsController@create')->name('transactions.create');
        Route::post('/store',                   'TransactionsController@store')->name('transactions.store');
    });

    Route::prefix('settings')->group(function() {
        Route::prefix('users')->namespace('Settings')->group(function () {
            Route::get('/',                             'SettingsController@users')->name('settings.users');
            Route::get('/create',                       'SettingsController@usersCreate')->name('settings.users.create');
            Route::post('/store',                       'SettingsController@usersStore')->name('settings.users.store');
            Route::get('/edit/{id}',                    'SettingsController@usersEdit')->name('settings.users.edit');
            Route::put('/update/{id}',                  'SettingsController@usersUpdate')->name('settings.users.update');
            Route::get('/delete/{id}',                  'SettingsController@usersDeleteDialog')->name('settings.users.delete');
            Route::delete('/delete/{id}',               'SettingsController@usersDelete')->name('settings.users.delete');
            Route::get('/download-file/{id}',           'SettingsController@usersDownloadFile')->name('settings.users.download-file');
            Route::get('/change-password/{id}',         'SettingsController@usersChangePasswordDialog')->name('settings.users.change-password');
            Route::patch('/change-password/{id}',       'SettingsController@usersChangePassword')->name('settings.users.change-password');
            Route::get('/manage-bills-and-cards/{id}',  'SettingsController@usersManageBillsAndCardsDialog')->name('settings.users.manage-bills-and-cards');
        });
        Route::prefix('bills')->namespace('Bills')->group(function () {
            Route::get('/get-all/{user_id}',            'BillsController@getAll')->name('settings.bills.get-all');
            Route::post('/store/{user_id}',             'BillsController@store')->name('settings.bills.store');
            Route::post('/detach-user/{id}',            'BillsController@detachUser');
            Route::post('/attach-user',                 'BillsController@attachUser')->name('settings.bills.attach-user');
        });
        Route::prefix('cards')->namespace('Cards')->group(function () {
            Route::get('/get-all/{user_id}',            'CardsController@getAll')->name('settings.cards.get-all');
            Route::post('/store/{user_id}/{bill_id}',   'CardsController@store');
            Route::delete('/delete/{id}',               'CardsController@delete');
            Route::patch('/toggle-active/{id}',         'CardsController@toggleActive');
        });
    });

    Route::get('/add-money', function() {
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
