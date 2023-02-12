<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'api'], function($routes){
    Route::post('/register', [UserController::class,'Register']);
    Route::post('/login', [UserController::class,'Login']);
    Route::get('/logout', [UserController::class,'Logout']);
    Route::get('/profile', [UserController::class,'Profile']);
    Route::post('/update-profile', [UserController::class,'UpdateProfile']);
    Route::get('/send-verify-mail/{email}', [UserController::class,'SendVerifyMail']);
    Route::get('/refresh-token', [UserController::class,'RefreshToken']);
});

Route::post('/forget-password', [UserController::class,'ForgetPassword']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
