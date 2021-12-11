<?php

// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Http\Controllers\Auth\WebAuthnLoginController;

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

// Route::get('twofaccount/{TwoFAccount}', 'TwoFAccountController@show');

Route::group(['middleware' => 'guest:web'], function () {
    Route::post('user/login', 'Auth\LoginController@login')->name('user.login');
});

Route::group(['middleware' => 'auth:web'], function () {
    Route::get('user/logout', 'Auth\LoginController@logout')->name('user.logout');
    Route::post('webauthn/register/options', [WebAuthnRegisterController::class, 'options'])->name('webauthn.register.options');
    Route::post('webauthn/register', [WebAuthnRegisterController::class, 'register'])->name('webauthn.register');
});

Route::group(['middleware' => ['guest:web', 'throttle:10,1']], function () {
    Route::post('webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
    Route::post('webauthn/login', [WebAuthnLoginController::class, 'login'])->name('webauthn.login');
});

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*')->name('landing');