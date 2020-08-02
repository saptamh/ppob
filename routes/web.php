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
        Route::get('/project', ['uses' => 'SalaryPaymentController@project'])->name('project');
        Route::post('/bonus', ['uses' => 'SalaryPaymentController@bonus'])->name('salary-bonus');
    });

    Route::group(['prefix' => 'purchase', 'as' => 'purchase.'], function () {
        Route::get('/', ['uses' => 'PurchaseController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'PurchaseController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'PurchaseController@create'])->name('add');
        Route::post('/store', ['uses' => 'PurchaseController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'PurchaseController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'PurchaseController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'purchase-goods', 'as' => 'purchase-goods.'], function () {
        Route::get('/', ['uses' => 'GoodPurchaseController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'GoodPurchaseController@index'])->name('data-table');
        Route::post('/store', ['uses' => 'GoodPurchaseController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'GoodPurchaseController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'GoodPurchaseController@destroy'])->name('destroy');
        Route::get('/template/{id}', ['uses' => 'GoodPurchaseController@template'])->name('template');
    });

    Route::group(['prefix' => 'bill', 'as' => 'bill.'], function () {
        Route::get('/', ['uses' => 'BillItemController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'BillItemController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'BillItemController@create'])->name('add');
        Route::post('/store', ['uses' => 'BillItemController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'BillItemController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'BillItemController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'petty-cash', 'as' => 'petty-cash.'], function () {
        Route::get('/', ['uses' => 'PettyCashController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'PettyCashController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'PettyCashController@create'])->name('add');
        Route::post('/store', ['uses' => 'PettyCashController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'PettyCashController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'PettyCashController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'nonpurchase', 'as' => 'nonpurchase.'], function () {
        Route::get('/', ['uses' => 'NonpurchaseController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'NonpurchaseController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'NonpurchaseController@create'])->name('add');
        Route::post('/store', ['uses' => 'NonpurchaseController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'NonpurchaseController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'NonpurchaseController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
        Route::get('/', ['uses' => 'PaymentController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'PaymentController@index'])->name('data-table');
        // Route::get('/create', ['uses' => 'PaymentController@create'])->name('add');
        Route::post('/store', ['uses' => 'PaymentController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'PaymentController@edit'])->name('edit');
        // Route::delete('/destroy/{id}', ['uses' => 'PaymentController@destroy'])->name('destroy');
        Route::get('/get-salary-payment', ['uses' => 'PaymentController@getSalaryPayment'])->name('get-project');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', ['uses' => 'UserController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'UserController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'UserController@create'])->name('add');
        Route::post('/store', ['uses' => 'UserController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'UserController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'UserController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('/', ['uses' => 'RoleController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'RoleController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'RoleController@create'])->name('add');
        Route::post('/store', ['uses' => 'RoleController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'RoleController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'RoleController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'approval', 'as' => 'approval.'], function () {
        Route::get('/nonpurchase', ['uses' => 'ApprovalController@nonpurchase'])->name('nonpurchase');
        Route::get('/purchase', ['uses' => 'ApprovalController@purchase'])->name('purchase');
        Route::get('/salary', ['uses' => 'ApprovalController@salaryPayment'])->name('salary-payment');
        Route::post('/store', ['uses' => 'ApprovalController@store'])->name('store');
    });

    Route::group(['prefix' => 'project-timeline', 'as' => 'project-timeline.'], function () {
        Route::get('/', ['uses' => 'ProjectTimelineController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectTimelineController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'ProjectTimelineController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectTimelineController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectTimelineController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectTimelineController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'project-daily', 'as' => 'project-daily.'], function () {
        Route::get('/{timeline_id}', ['uses' => 'ProjectDailyController@index'])->name('main');
        Route::get('/data-table/{timeline_id}', ['uses' => 'ProjectDailyController@index'])->name('data-table');
        Route::get('/create/{timeline_id}', ['uses' => 'ProjectDailyController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectDailyController@store'])->name('store');
        Route::get('/edit/{timeline_id}/{id}', ['uses' => 'ProjectDailyController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectDailyController@destroy'])->name('destroy');
        Route::post('/data-table-kpi', ['uses' => 'ProjectDailyController@dataKpi'])->name('data-table-kpi');
    });

    Route::group(['prefix' => 'project-item', 'as' => 'project-item.'], function () {
        Route::get('/', ['uses' => 'ProjectItemController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectItemController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'ProjectItemController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectItemController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectItemController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectItemController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'project-job', 'as' => 'project-job.'], function () {
        Route::get('/', ['uses' => 'ProjectJobController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectJobController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'ProjectJobController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectJobController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectJobController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectJobController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'project-zone', 'as' => 'project-zone.'], function () {
        Route::get('/', ['uses' => 'ProjectZoneController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'ProjectZoneController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'ProjectZoneController@create'])->name('add');
        Route::post('/store', ['uses' => 'ProjectZoneController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'ProjectZoneController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'ProjectZoneController@destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'kpi', 'as' => 'kpi.'], function () {
        Route::get('/', ['uses' => 'KpiController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'KpiController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'KpiController@create'])->name('add');
        Route::post('/store', ['uses' => 'KpiController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'KpiController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'KpiController@destroy'])->name('destroy');
        Route::post('/kpi-employee', ['uses' => 'KpiController@kpiEmployee'])->name('kpi-employee');
    });

    Route::group(['prefix' => 'bonus', 'as' => 'bonus.'], function () {
        Route::get('/', ['uses' => 'BonusController@index'])->name('main');
        Route::get('/data-table', ['uses' => 'BonusController@index'])->name('data-table');
        Route::get('/create', ['uses' => 'BonusController@create'])->name('add');
        Route::post('/store', ['uses' => 'BonusController@store'])->name('store');
        Route::get('/edit/{id}', ['uses' => 'BonusController@edit'])->name('edit');
        Route::delete('/destroy/{id}', ['uses' => 'BonusController@destroy'])->name('destroy');
    });

});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
