<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminFeatureApiController;
use App\Http\Controllers\Api\FrontendHubController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [HomeController::class, 'api'])->name('api.frontend.home');
Route::get('/for-rent', [FrontendHubController::class, 'rent'])->name('api.frontend.rent');
Route::get('/sell', [FrontendHubController::class, 'sell'])->name('api.frontend.sell');

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->name('api.admin.login');

    Route::middleware(['auth:sanctum', 'admin.api'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('api.admin.logout');
        Route::get('/dashboard', [AdminFeatureApiController::class, 'dashboard'])->name('api.admin.dashboard');
        Route::delete('/property-media/{media}', [AdminFeatureApiController::class, 'destroyPropertyMedia'])->name('api.admin.property-media.destroy');

        Route::get('/{resource}', [AdminFeatureApiController::class, 'index'])->name('api.admin.resources.index');
        Route::post('/{resource}', [AdminFeatureApiController::class, 'store'])->name('api.admin.resources.store');
        Route::get('/{resource}/{id}', [AdminFeatureApiController::class, 'show'])->whereNumber('id')->name('api.admin.resources.show');
        Route::match(['put', 'patch'], '/{resource}/{id}', [AdminFeatureApiController::class, 'update'])->whereNumber('id')->name('api.admin.resources.update');
        Route::delete('/{resource}/{id}', [AdminFeatureApiController::class, 'destroy'])->whereNumber('id')->name('api.admin.resources.destroy');
    });
});
