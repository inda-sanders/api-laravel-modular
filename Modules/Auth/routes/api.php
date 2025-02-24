<?php

use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Modules\Auth\Http\Controllers\DepartmentController;
use Modules\Auth\Http\Controllers\UserController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

/**
 * Route without any token and api key validation
 */

Route::prefix('v1')->group(function () {
    Route::post('/generate_token', [AuthController::class, 'generateAppKey']);
});

/*
 *  Route for register & login without token authentication
 */

Route::middleware(['checkToken:public,transactional'])->prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

/*
 *  Route with token and api key validation
 */
Route::middleware(['auth:api', 'checkToken:public'])->prefix('v1')->group(function () {


    Route::get('user/{id}', [UserController::class, 'getOne'])->name('user.getOne');
    Route::apiResource('user', UserController::class)->names('user')->except(['show']);

    Route::get('department/{id}', [DepartmentController::class, 'getOne'])->name('department.getOne');
    Route::apiResource('department', DepartmentController::class)->names('department')->except(['show']);

    /**
     * middleware to svalering database
     */
    Route::middleware(['slavering:1'])->post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-only', function () {
            return response()->json(['message' => auth()->user()]);
        });
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user-only', function () {
            return response()->json(['message' => 'Welcome, User!']);
        });
    });
});
