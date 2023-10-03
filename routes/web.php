<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');

Route::middleware(['auth'])->group(function () {
    # Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    # Dashboard
    Route::get('', [DashboardController::class, 'index'])->name('dashboard');

    # Customer
    Route::prefix('customer')->controller(CustomerController::class)->group(function () {
        Route::get('', 'index')->name('customer');
        Route::post('store', 'store')->name('customer.store');
        Route::get('show/{id}', 'show')->name('customer.show');
        Route::post('update/{id}', 'update')->name('customer.update');
        Route::post('delete/{id}', 'delete')->name('customer.delete');
    });

    # Customer
    Route::prefix('product')->controller(ProductController::class)->group(function () {
        Route::get('', 'index')->name('product');
        Route::post('store', 'store')->name('product.store');
        Route::get('show/{id}', 'show')->name('product.show');
        Route::post('update/{id}', 'update')->name('product.update');
        Route::post('delete/{id}', 'delete')->name('product.delete');
    });

    # Transaction
    Route::prefix('transaction')->controller(TransactionController::class)->group(function () {
        Route::get('', 'index')->name('transaction');
        Route::post('store', 'store')->name('transaction.store');
    });

    # Report
    Route::prefix('report')->controller(ReportController::class)->group(function () {
        Route::get('', 'index')->name('report');
        Route::get('export-excel', 'export')->name('report.export');
        Route::post('print', 'print')->name('report.print');
    });

    # Setting
    Route::prefix('setting')->controller(SettingController::class)->group(function () {
        Route::get('', 'index')->name('setting');
        Route::get('account', 'account')->name('setting.account');
        Route::post('update-saldo', 'updateSaldo')->name('setting.update.saldo');
        Route::post('reset-password', 'resetPassword')->name('setting.reset.password');
    });
});
