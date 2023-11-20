<?php

use App\Http\Controllers\Contact\ContactController;
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
        'person' => PersonController::class,

    ]);
    Route::prefix('person')->name('person.')->group(function () {
        Route::put('/{person}/favorite', [PersonController::class, 'favorite'])->name('favorite');
        Route::delete('/{person}/force', [PersonController::class, 'forceDestroy'])->name('forceDestroy');
    });
});

Route::controller(ContactController::class)->group(function () {
    Route::apiResources([
        'contacts' => ContactController::class,
    ]);

    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::put('/{contact}/favorite', [ContactController::class, 'favorite'])->name('favorite');
        Route::delete('/{contact}/force', [ContactController::class, 'forceDestroy'])->name('forceDestroy');
    });
});
