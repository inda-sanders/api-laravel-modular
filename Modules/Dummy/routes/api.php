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

Route::prefix('v1')->group(function () {
    Route::get('dummy/{id}', [DummyController::class, 'getOne'])->name('dummy.getOne');
    Route::apiResource('dummy', DummyController::class)->names('dummy')->except(['show']);
});
