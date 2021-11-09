<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/////////////////////////////////////////////////////////////////
////////////////AUTH ROUTES///////////////////////////////
///////////////////////////////////////////////////////////////

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify', [AuthController::class, 'verify']);
    Route::put('/reset-password/{token}', [AuthController::class, 'resetPassword']);
});

Route::group([
    'middleware' => ['jwt.verify'],
    'prefix' => 'auth'

], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});


//////////////////////////////////////////////////////////////////////////////////
/////////////USER ROUTES////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

Route::group([
    'middleware' => ['jwt.verify'],
    'prefix' => 'user'

],  function($router) {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/update-avatar', [UserController::class, 'updateProfilePicture']);
    Route::post('/update-cover-photo', [UserController::class, 'updateProfileCoverPhoto']);
    Route::put('/update-profile', [UserController::class, 'updateProfile']);
    Route::put('/chnage-password', [UserController::class, 'changePassword']);
});
