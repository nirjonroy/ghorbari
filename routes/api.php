<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminFeatureApiController;
use App\Http\Controllers\Api\FrontendHubController;
use App\Http\Controllers\Frontend\AgentController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CalculatorController;
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
Route::get('/for-sale', [FrontendHubController::class, 'forSale'])->name('api.frontend.for-sale');
Route::get('/for-rent', [FrontendHubController::class, 'rent'])->name('api.frontend.rent');
Route::get('/sell', [FrontendHubController::class, 'sell'])->name('api.frontend.sell');
Route::get('/calculator', [CalculatorController::class, 'api'])->name('api.frontend.calculator');
Route::get('/real-estate-agents', [AgentController::class, 'apiIndex'])->name('api.frontend.agents.index');
Route::get('/open-houses', [FrontendHubController::class, 'openHouses'])->name('api.frontend.open-houses');
Route::get('/early-access', [FrontendHubController::class, 'earlyAccess'])->name('api.frontend.early-access');
Route::get('/blog', [BlogController::class, 'apiIndex'])->name('api.frontend.blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'apiShow'])->name('api.frontend.blog.show');
Route::get('/property/for-sale', [FrontendHubController::class, 'buySearch'])
    ->name('api.frontend.property.buy-search');
Route::get('/property-details/{property}', [FrontendHubController::class, 'propertyDetail'])
    ->where('property', '.+-[0-9]+')
    ->name('api.frontend.property.show');
Route::get('/property/for-sale/residential/land-plot/{city}', [FrontendHubController::class, 'landSaleCity'])
    ->name('api.frontend.property.land-sale-city');
Route::get('/property/{purpose}/{type}/{district}/{city}/{localArea}', [FrontendHubController::class, 'purposeTypeLocalArea'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('api.frontend.property.purpose-type-local-area');
Route::get('/property/{purpose}/{type}/{district}/{city}', [FrontendHubController::class, 'purposeTypeCity'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('api.frontend.property.purpose-type-city');
Route::get('/property/{purpose}/{type}/{district}', [FrontendHubController::class, 'purposeTypeDistrict'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('api.frontend.property.purpose-type-district');
Route::get('/property/{purpose}/{category}/{type}', [FrontendHubController::class, 'type'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('category', 'residential|commercial|land|industrial')
    ->name('api.frontend.property.type');
Route::get('/property/{purpose}/{category}', [FrontendHubController::class, 'category'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('category', 'residential|commercial|land|industrial')
    ->name('api.frontend.property.category');
Route::get('/property/{district}/{city}/{localArea}', [FrontendHubController::class, 'localArea'])
    ->name('api.frontend.property.local-area');
Route::get('/property/{district}/{city}', [FrontendHubController::class, 'city'])
    ->name('api.frontend.property.city');
Route::get('/property/{district}', [FrontendHubController::class, 'district'])
    ->name('api.frontend.property.district');

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
