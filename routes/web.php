<?php

use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('Admin.dashboard');
})->middleware('auth:admin')->name('admin.dashboard');

Route::get('/admin/site-info', [SiteInfoController::class, 'index'])
    ->middleware('auth:admin')
    ->name('admin.site-info.index');
Route::get('/admin/site-info/edit', [SiteInfoController::class, 'edit'])
    ->middleware('auth:admin')
    ->name('admin.site-info.edit');
Route::put('/admin/site-info', [SiteInfoController::class, 'update'])
    ->middleware('auth:admin')
    ->name('admin.site-info.update');

Route::resource('/admin/sliders', SliderController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->names('admin.sliders');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
