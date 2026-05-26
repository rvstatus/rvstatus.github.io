<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseDashBoardController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\WorkCategoryController;
use App\Http\Controllers\WorkTypeController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'homePage'])->name('home.redirect');

// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login_process', [AuthController::class, 'login_process'])->name('login_process');
// Route::post('/login_process', [AuthController::class, 'login_process']);

// Register
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register_process', [AuthController::class, 'register_process'])->name('register_process');

// Logout
Route::get('/logout', [AuthController::class, 'logout']);

// forgot password process start
Route::get('/forgot-password', [AuthController::class, 'forgot_password']);
Route::post('/forgot-password', [AuthController::class, 'send_reset_link']);

Route::get('/reset-password', [AuthController::class, 'reset_password_form'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'reset_password'])->name('password.reset');
// forgot password process end

// expense screen start
Route::middleware(['auth'])->group(function () {
    Route::get('/expense_list', [ExpenseController::class, 'expense_list'])->name('expense_list');
    Route::get('/expense_register', [ExpenseController::class, 'expense_register']);
    Route::post('/exp_reg_process', [ExpenseController::class, 'exp_reg_process']);
});
// expense screen end

// expense dashboard screen start
Route::middleware(['auth'])->group(function () {
    Route::any('/expense_dashboard', [ExpenseDashBoardController::class, 'expense_dashboard']);
});
// expense dashboard screen end

// project type screen start
Route::middleware(['auth'])->group(function () {
    Route::get('/project_type_list', [ProjectTypeController::class, 'project_type_list']);
    Route::post('/project_type_toggle', [ProjectTypeController::class, 'toggle_status']);
    Route::post('/project_type_register', [ProjectTypeController::class, 'register']);
    Route::post('/project_type_update', [ProjectTypeController::class, 'update']);
    Route::post('/project_type_get_by_id', [ProjectTypeController::class, 'get_by_id']);
});
// project type screen end

// work category screen start
Route::middleware(['auth'])->group(function () {
    Route::get('/work_category_list', [WorkCategoryController::class, 'work_category_list']);
    Route::post('/work_category_toggle', [WorkCategoryController::class, 'toggle_status']);
    Route::post('/work_category_register', [WorkCategoryController::class, 'register']);
    Route::post('/work_category_update', [WorkCategoryController::class, 'update']);
    Route::post('/work_category_get_by_id', [WorkCategoryController::class, 'get_by_id']);
});
// work category screen end

// work type screen start
Route::middleware(['auth'])->group(function () {
    Route::get('/work_type_list', [WorkTypeController::class, 'work_type_list']);
    Route::post('/work_type_toggle', [WorkTypeController::class, 'toggle_status']);
    Route::post('/work_type_register', [WorkTypeController::class, 'register']);
    Route::post('/work_type_update', [WorkTypeController::class, 'update']);
    Route::post('/work_type_get_by_id', [WorkTypeController::class, 'get_by_id']);
});
// work type screen end