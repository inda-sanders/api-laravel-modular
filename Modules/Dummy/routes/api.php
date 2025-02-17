<?php

use Illuminate\Support\Facades\Route;
use Modules\Dummy\Http\Controllers\DummyController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('dummy', DummyController::class)->names('dummy');
// });

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->getRoleNames();
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-only', function () {
            return response()->json(['message' => 'Welcome, Admin!']);
        });
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user-only', function () {
            return response()->json(['message' => 'Welcome, User!']);
        });
    });
});
