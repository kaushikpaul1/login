<?php

use App\Http\Controllers\admincontroller;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\phpmailercontroller;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\CaptchaController as CaptchaCaptchaController;

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



// Route::middleware('guest')->group(function () {
Route::group(['middleware' => 'guest'], function () {

    //For login Route
    Route::get('/login', [admincontroller::class, 'login']);
    // for Registratin Route
    Route::get('/register', [admincontroller::class, 'register']);
    // for registration data store route
    Route::post('/register', [admincontroller::class, 'postregister'])->name('register.store');
    //for login data store route
    Route::post('/login', [admincontroller::class, 'postlogin'])->name('login.store');
});


Route::group(['middleware' => 'auth'], function () {
    //For dashboard
    Route::get('/dashboard', [admincontroller::class, 'dashboard']);
    // Route::get('/dashboard',[admincontroller::class,'dashboard'])->name('dashboard.view');
});
//For captcha
Route::get('/', [CaptchaController::class, 'index']);
Route::get('/reload-captcha', [CaptchaController::class, 'reloadCaptcha']);
//For captcha post
// Route::psot('register.store',[CaptchaCaptchaController::class,'post']);
//For forgot password view
Route::get('/forgot', [admincontroller::class, 'forgotview']);
//for forgot password post
Route::post('/forgot', [admincontroller::class, 'forgotpost'])->name('forgot.store');

//For Reset password
Route::get('/reset/{token}', [admincontroller::class, 'reset']);
Route::post('/reset/{token}', [admincontroller::class, 'postreset'])->name('reset.store');
Route::group(['middleware' => 'auth'], function () {
    //For logout
    Route::get('/logout', [admincontroller::class, 'logout'])->name('logout');
});
