<?php

use App\Http\Controllers\AdminManageController;
use App\Http\Controllers\CompanyManageController;
use App\Http\Controllers\CurrencyManageController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\FTPController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ScreenShotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\DataController;
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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::group(['prefix' => 'spadmin'], function () {
    Route::get('/companies',  [CompanyManageController::class, 'index'])->name('companyManagement')->middleware('auth');
    Route::post('/company',  [CompanyManageController::class, 'add'])->middleware('auth');
    Route::put('/company',  [CompanyManageController::class, 'edit'])->middleware('auth');
    Route::delete('/company',  [CompanyManageController::class, 'delete'])->middleware('auth');

    Route::get('/admins',  [AdminManageController::class, 'index'])->name('adminManagement')->middleware('auth');
    Route::post('/admin',  [AdminManageController::class, 'add'])->middleware('auth');
    Route::put('/admin',  [AdminManageController::class, 'edit'])->middleware('auth');
    Route::delete('/admin',  [AdminManageController::class, 'delete'])->middleware('auth');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/users',  [UserManageController::class, 'index'])->name('userManagement')->middleware('auth');
    Route::post('/user',  [UserManageController::class, 'add'])->middleware('auth');
    Route::put('/user',  [UserManageController::class, 'edit'])->middleware('auth');
    Route::delete('/user',  [UserManageController::class, 'delete'])->middleware('auth');

    Route::get('/currencies',  [CurrencyManageController::class, 'index'])->name('currencyManagement')->middleware('auth');
    Route::post('/currency',  [CurrencyManageController::class, 'add'])->middleware('auth');
    Route::put('/currency',  [CurrencyManageController::class, 'edit'])->middleware('auth');
    Route::delete('/currency',  [CurrencyManageController::class, 'delete'])->middleware('auth');
});

Route::get('/exchange',  [ExchangeController::class, 'index'])->name('exchange')->middleware('auth');
Route::get('/newexchange',  [ExchangeController::class, 'new_exchange'])->name('new_exchange')->middleware('auth');
Route::get('/printexchange',  [ExchangeController::class, 'print_exchange'])->name('print_exchange')->middleware('auth');

Route::get('/report',  [ExchangeController::class, 'report'])->name('exchangelist')->middleware('auth');
Route::get('/exchangelist',  [ExchangeController::class, 'get_list'])->middleware('auth');
Route::get('/exchange-export',  [ExchangeController::class, 'export_list'])->middleware('auth');
