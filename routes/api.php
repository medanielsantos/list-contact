<?php

use App\Http\Controllers\Person\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(PersonController::class)->group(function () {
    Route::apiResources([
        'people' => PersonController::class,

    ]);

    Route::prefix('people')->name('people.')->group(function () {
        Route::delete('/{person}/force', [PersonController::class, 'forceDestroy'])->name('forceDestroy');
    });

});
