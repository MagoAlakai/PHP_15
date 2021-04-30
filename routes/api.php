<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;



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

//Routes de JWT integradas en Routes de BREEZE para autenticación

Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/refresh-token', [AuthenticatedSessionController::class, 'refreshToken']);

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('jwt.verify')
            ->name('logout');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->middleware('auth');

Route::get('/user/{email}', [UserController::class, 'getUser']);



//GET

// Route::get('/register', [RegisteredUserController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('register');

// Route::get('/login', [AuthenticatedSessionController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('login');

// Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('password.request');

// Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('password.reset');

// Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
//                 ->middleware('auth')
//                 ->name('verification.notice');

// Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//                 ->middleware(['auth', 'signed', 'throttle:6,1'])
//                 ->name('verification.verify');

// Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
//                 ->middleware('auth')
//                 ->name('password.confirm');
