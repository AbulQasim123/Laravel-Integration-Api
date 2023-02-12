<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

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

Route::get('/register', function () {
    return view('apiintegration.register');
});
Route::get('/login', function () {
    return view('apiintegration.login');
});
Route::get('/profile', function () {
    return view('apiintegration.profile');
});

Route::get('verify-email/{token}',[UserController::class,'VerifyMail']);
Route::get('password-reset',[UserController::class,'PasswordResetload']);
Route::post('password-reset',[UserController::class,'PasswordResetSubmit']);

Route::get('forget-password', function(){
    return view('apiintegration.forgetpassword');
});