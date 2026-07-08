<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogPageController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
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

Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('auth:admin')
    ->name('admin.users.index');
Route::get('/admin/users/{user}', [UserController::class, 'show'])
    ->middleware('auth:admin')
    ->name('admin.users.show');

Route::resource('/admin/divisions', DivisionController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->names('admin.divisions');
Route::resource('/admin/districts', DistrictController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->names('admin.districts');
Route::resource('/admin/areas', AreaController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->names('admin.areas');

Route::resource('/admin/blog/categories', BlogCategoryController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->parameters(['categories' => 'blogCategory'])
    ->names('admin.blog-categories');
Route::resource('/admin/blog/posts', BlogPostController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->parameters(['posts' => 'blogPost'])
    ->names('admin.blog-posts');
Route::resource('/admin/blog/comments', BlogCommentController::class)
    ->except('show')
    ->middleware('auth:admin')
    ->parameters(['comments' => 'blogComment'])
    ->names('admin.blog-comments');
Route::get('/admin/blog/page-settings', [BlogPageController::class, 'index'])
    ->middleware('auth:admin')
    ->name('admin.blog-pages.index');
Route::get('/admin/blog/page-settings/edit', [BlogPageController::class, 'edit'])
    ->middleware('auth:admin')
    ->name('admin.blog-pages.edit');
Route::put('/admin/blog/page-settings', [BlogPageController::class, 'update'])
    ->middleware('auth:admin')
    ->name('admin.blog-pages.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
