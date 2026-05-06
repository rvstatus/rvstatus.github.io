<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::auth();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// expense screen start
Route::get('/expense_list', 'ExpenseController@expense_list');
Route::get('/expense_register', 'ExpenseController@expense_register');
Route::post('/exp_reg_process', 'ExpenseController@exp_reg_process');
// expense screen end

// expense dashboard screen start
Route::any('/expense_dashboard', 'ExpenseDashBoardController@expense_dashboard');

// expense dashboard screen end

// project type screen start
Route::group(['middleware' => 'auth'], function () {
    Route::get('/project_type_list', 'ProjectTypeController@project_type_list');
    Route::post('/project_type_toggle', 'ProjectTypeController@toggle_status');
    Route::post('/project_type_register', 'ProjectTypeController@register');
    Route::post('/project_type_update', 'ProjectTypeController@update');
    Route::post('/project_type_get_by_id', 'ProjectTypeController@get_by_id');
});
// project type screen end

// work category screen start
Route::group(['middleware' => 'auth'], function () {
    Route::get('/work_category_list', 'WorkCategoryController@work_category_list');
    Route::post('/work_category_toggle', 'WorkCategoryController@toggle_status');
    Route::post('/work_category_register', 'WorkCategoryController@register');
    Route::post('/work_category_update', 'WorkCategoryController@update');
    Route::post('/work_category_get_by_id', 'WorkCategoryController@get_by_id');
});
// work category screen end

// work type screen start
Route::group(['middleware' => 'auth'], function () {
    Route::get('/work_type_list', 'WorkTypeController@work_type_list');
    Route::post('/work_type_toggle', 'WorkTypeController@toggle_status');
    Route::post('/work_type_register', 'WorkTypeController@register');
    Route::post('/work_type_update', 'WorkTypeController@update');
    Route::post('/work_type_get_by_id', 'WorkTypeController@get_by_id');
});
// work type screen end