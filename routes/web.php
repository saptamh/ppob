<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', 'UserController@loginPage')->name('login');
Route::post('/login-check', 'UserController@loginVerify')->name('login-check');

Route::group(['middleware' => 'user'], function() {
    Route::get('/', [
        'uses' => 'HomeController@index',
    ])->name('home');

    Route::get('/logout', [
        'uses' => 'UserController@logout',
    ])->name('logout');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', ['uses' => 'UserController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'UserController@dataGrid'])->name('data-table');
        Route::post('/store', ['uses' => 'UserController@store'])->name('store');
        Route::delete('/destroy', ['uses' => 'UserController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'province', 'as' => 'province.'], function () {
        Route::get('/', ['uses' => 'AreaController@indexProvince'])->name('main');
        Route::get('/data-table', ['uses' => 'AreaController@dataGridProvince'])->name('data-table');
        Route::post('/store', ['uses' => 'AreaController@storeProvince'])->name('store');
        Route::delete('/destroy', ['uses' => 'AreaController@destroyProvince'])->name('destroy');
        Route::get('/combobox', ['uses' => 'AreaController@comboProvince'])->name('combobox');
    });

    Route::group(['prefix' => 'city', 'as' => 'city.'], function () {
        Route::get('/', ['uses' => 'AreaController@indexCity'])->name('main');
        Route::get('/data-table', ['uses' => 'AreaController@dataGridCity'])->name('data-table');
        Route::post('/store', ['uses' => 'AreaController@storeCity'])->name('store');
        Route::delete('/destroy', ['uses' => 'AreaController@destroyCity'])->name('destroy');
        Route::get('/combobox', ['uses' => 'AreaController@comboCity'])->name('combobox');
    });

    Route::group(['prefix' => 'district', 'as' => 'district.'], function () {
        Route::get('/', ['uses' => 'AreaController@indexDistrict'])->name('main');
        Route::get('/data-table', ['uses' => 'AreaController@dataGridDistrict'])->name('data-table');
        Route::post('/store', ['uses' => 'AreaController@storeDistrict'])->name('store');
        Route::delete('/destroy', ['uses' => 'AreaController@destroyDistrict'])->name('destroy');
        Route::get('/combobox', ['uses' => 'AreaController@comboDistrict'])->name('combobox');
    });

    Route::group(['prefix' => 'village', 'as' => 'village.'], function () {
        Route::get('/', ['uses' => 'AreaController@indexVillage'])->name('main');
        Route::get('/data-table', ['uses' => 'AreaController@dataGridVillage'])->name('data-table');
        Route::post('/store', ['uses' => 'AreaController@storeVillage'])->name('store');
        Route::delete('/destroy', ['uses' => 'AreaController@destroyVillage'])->name('destroy');
        Route::get('/combobox', ['uses' => 'AreaController@comboVillage'])->name('combobox');
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('/', ['uses' => 'CustomerController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'CustomerController@dataGrid'])->name('data-table');
        Route::post('/store', ['uses' => 'CustomerController@store'])->name('store');
        Route::delete('/destroy', ['uses' => 'CustomerController@destroy'])->name('destroy');
        Route::get('/vehicles', ['uses' => 'CustomerController@vehicle'])->name('vehicle');
    });

    Route::group(['prefix' => 'mapping', 'as' => 'mapping.'], function () {
        Route::get('/', ['uses' => 'MappingController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'MappingController@dataGrid'])->name('data-table');
    });

    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get('/late-pay', ['uses' => 'TransactionController@latePay'])->name('late-pay-main');
        Route::get('/late-pay-data-table', ['uses' => 'TransactionController@latePayDataGrid'])->name('late-pay-data-table');
    });

});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
