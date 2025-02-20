<?php

use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Http\Request;

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

/*
 *  Route for register & login without token authentication
 */

Route::prefix('v1')->group(function () {
    Route::middleware(['checkToken:panel,web'])->post('/register', [AuthController::class, 'register']);
    Route::middleware(['checkToken:panel,web'])->post('/login', [AuthController::class, 'login']);
    Route::post('/generate_token', [AuthController::class, 'generateAppKey']);
});

/*
 *  Route for register & login without token authentication
 */
Route::middleware(['auth:api', 'checkToken'])->prefix('v1')->group(function () {
    Route::middleware(['slavering:1'])->post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->getRoleNames();
    });

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
