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

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
    Route::get('/', [
        'uses' => 'HomeController@index',
    ])->name('home');

    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::get('/', ['uses' => 'EmployeeController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'EmployeeController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'EmployeeController@create'])->name('add');
        Route::post('/store', ['uses' => 'EmployeeController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'EmployeeController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'EmployeeController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'employee-family', 'as' => 'employee-family.'], function () {
        Route::get('/', ['uses' => 'FamilyController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'FamilyController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'FamilyController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'FamilyController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'FamilyController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'FamilyController@template'])->name('template');
    });

    Route::group(['prefix' => 'employee-salary', 'as' => 'employee-salary.'], function () {
        Route::get('/', ['uses' => 'SalaryController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'SalaryController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'SalaryController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'SalaryController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'SalaryController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'SalaryController@template'])->name('template');
    });

    Route::group(['prefix' => 'employee-level', 'as' => 'employee-level.'], function () {
        Route::get('/', ['uses' => 'LevelController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'LevelController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'LevelController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'LevelController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'LevelController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'LevelController@template'])->name('template');
    });

    Route::group(['prefix' => 'employee-experience', 'as' => 'employee-experience.'], function () {
        Route::get('/', ['uses' => 'EmployeeWorkExperienceController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'EmployeeWorkExperienceController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'EmployeeWorkExperienceController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'EmployeeWorkExperienceController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'EmployeeWorkExperienceController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'EmployeeWorkExperienceController@template'])->name('template');
    });

    Route::group(['prefix' => 'document-employee', 'as' => 'document-employee.'], function () {
        Route::get('/', ['uses' => 'EmployeeDocumentController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'EmployeeDocumentController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'EmployeeDocumentController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'EmployeeDocumentController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'EmployeeDocumentController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'EmployeeDocumentController@template'])->name('template');
    });

    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::get('/', ['uses' => 'ProjectController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'ProjectController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'log-project', 'as' => 'log-project.'], function () {
        Route::get('/', ['uses' => 'ProjectHistoricalController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectHistoricalController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'ProjectHistoricalController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectHistoricalController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectHistoricalController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'ProjectHistoricalController@template'])->name('template');
    });

    Route::group(['prefix' => 'document-project', 'as' => 'document-project.'], function () {
        Route::get('/', ['uses' => 'ProjectDocumentController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectDocumentController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'ProjectDocumentController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectDocumentController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectDocumentController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'ProjectDocumentController@template'])->name('template');
    });

    Route::group(['prefix' => 'value-project', 'as' => 'value-project.'], function () {
        Route::get('/', ['uses' => 'ProjectValueController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectValueController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'ProjectValueController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectValueController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectValueController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'ProjectValueController@template'])->name('template');
    });

    Route::group(['prefix' => 'progress-project', 'as' => 'progress-project.'], function () {
        Route::get('/', ['uses' => 'ProjectProgressController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectProgressController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'ProjectProgressController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectProgressController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectProgressController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'ProjectProgressController@template'])->name('template');
    });

    Route::group(['prefix' => 'salary-payment', 'as' => 'salary-payment.'], function () {
        Route::get('/', ['uses' => 'SalaryPaymentController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'SalaryPaymentController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'SalaryPaymentController@create'])->name('add');
        Route::post('/store', ['uses' => 'SalaryPaymentController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'SalaryPaymentController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'SalaryPaymentController@destroy'])->name('destroy');
        Route::get('/salary/{employee_id}', ['uses' => 'SalaryPaymentController@salary'])->name('salary');
    });
});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
