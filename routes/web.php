<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
    return view('welcome');
});

Auth::routes([
    'verify' => true, 'reset' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('google/auth')->group(function () {
    Route::get('redirect', [LoginController::class, 'redirectToProvider'])->name('google.auth.login');
    Route::get('callback', [LoginController::class, 'handleProviderCallback']);
});

Route::prefix('reset-password/email')->group(function () {
    Route::get('/', [ForgotPasswordController::class, 'forgetPasswordEmailView'])->name('reset.password.email');
    Route::post('/aksi', [ForgotPasswordController::class, 'forgetPasswordStore'])->name('reset.password.aksi');
});

Route::prefix('reset-password')->group(function () {
    Route::get('token/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password.getEmail');
    Route::post('aksi', [ForgotPasswordController::class, 'resetPasswordStore'])->name('reset.password.update');
});

Route::prefix('login-whatsapp')->group(function () {
    Route::get('/', [LoginController::class, 'loginWhatsappForm'])->name('login-whatsapp.index');
    Route::post('/verifikasi', [LoginController::class, 'loginWhatsappVerifikasi'])->name('login-whatsapp.verifikasi');
    Route::post('/aksi', [LoginController::class, 'loginWhatsappAksi'])->name('login-whatsapp.aksi');
});
