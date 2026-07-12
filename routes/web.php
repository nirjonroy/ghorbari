<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\AgentProfileController;
use App\Http\Controllers\Admin\ApiTesterController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogPageController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\RoleController;
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

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth:admin', 'permission:manage dashboard,admin'])
    ->name('admin.dashboard');

Route::get('/admin/api-tester', [ApiTesterController::class, 'index'])
    ->middleware(['auth:admin', 'permission:manage permissions,admin'])
    ->name('admin.api-tester.index');

Route::get('/admin/site-info', [SiteInfoController::class, 'index'])
    ->middleware(['auth:admin', 'permission:manage site info,admin'])
    ->name('admin.site-info.index');
Route::get('/admin/site-info/edit', [SiteInfoController::class, 'edit'])
    ->middleware(['auth:admin', 'permission:manage site info,admin'])
    ->name('admin.site-info.edit');
Route::put('/admin/site-info', [SiteInfoController::class, 'update'])
    ->middleware(['auth:admin', 'permission:manage site info,admin'])
    ->name('admin.site-info.update');

Route::resource('/admin/abouts', AboutController::class)
    ->middleware(['auth:admin', 'permission:manage about,admin'])
    ->names('admin.abouts');

Route::resource('/admin/sliders', SliderController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage sliders,admin'])
    ->names('admin.sliders');

Route::resource('/admin/contacts', ContactMessageController::class)
    ->only(['index', 'edit', 'update', 'destroy'])
    ->middleware(['auth:admin', 'permission:manage contacts,admin'])
    ->parameters(['contacts' => 'contact'])
    ->names('admin.contacts');

Route::resource('/admin/agencies', AgencyController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage agencies,admin'])
    ->names('admin.agencies');
Route::resource('/admin/agent-profiles', AgentProfileController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage agents,admin'])
    ->names('admin.agent-profiles');

Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware(['auth:admin', 'permission:manage users,admin'])
    ->name('admin.users.index');
Route::get('/admin/users/{user}', [UserController::class, 'show'])
    ->middleware(['auth:admin', 'permission:manage users,admin'])
    ->name('admin.users.show');

Route::resource('/admin/property-types', PropertyTypeController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage properties|manage property types,admin'])
    ->names('admin.property-types');
Route::resource('/admin/amenities', AmenityController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage properties|manage amenities,admin'])
    ->names('admin.amenities');
Route::resource('/admin/properties', PropertyController::class)
    ->middleware(['auth:admin', 'permission:manage properties,admin'])
    ->names('admin.properties');
Route::delete('/admin/property-media/{media}', [PropertyController::class, 'destroyMedia'])
    ->middleware(['auth:admin', 'permission:manage properties,admin'])
    ->name('admin.property-media.destroy');

Route::resource('/admin/roles', RoleController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage roles,admin'])
    ->names('admin.roles');
Route::resource('/admin/permissions', PermissionController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage permissions,admin'])
    ->names('admin.permissions');
Route::get('/admin/admin-roles', [AdminRoleController::class, 'index'])
    ->middleware(['auth:admin', 'permission:assign admin roles,admin'])
    ->name('admin.admin-roles.index');
Route::get('/admin/admin-roles/create', [AdminRoleController::class, 'create'])
    ->middleware(['auth:admin', 'permission:assign admin roles,admin'])
    ->name('admin.admin-roles.create');
Route::post('/admin/admin-roles', [AdminRoleController::class, 'store'])
    ->middleware(['auth:admin', 'permission:assign admin roles,admin'])
    ->name('admin.admin-roles.store');
Route::get('/admin/admin-roles/{admin}/edit', [AdminRoleController::class, 'edit'])
    ->middleware(['auth:admin', 'permission:assign admin roles,admin'])
    ->name('admin.admin-roles.edit');
Route::put('/admin/admin-roles/{admin}', [AdminRoleController::class, 'update'])
    ->middleware(['auth:admin', 'permission:assign admin roles,admin'])
    ->name('admin.admin-roles.update');

Route::resource('/admin/divisions', DivisionController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage locations|manage divisions,admin'])
    ->names('admin.divisions');
Route::resource('/admin/districts', DistrictController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage locations|manage districts,admin'])
    ->names('admin.districts');
Route::resource('/admin/areas', AreaController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage locations|manage areas,admin'])
    ->names('admin.areas');

Route::resource('/admin/blog/categories', BlogCategoryController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage blog|manage blog categories,admin'])
    ->parameters(['categories' => 'blogCategory'])
    ->names('admin.blog-categories');
Route::resource('/admin/blog/posts', BlogPostController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage blog|manage blog posts,admin'])
    ->parameters(['posts' => 'blogPost'])
    ->names('admin.blog-posts');
Route::resource('/admin/blog/comments', BlogCommentController::class)
    ->only(['index', 'update', 'destroy'])
    ->middleware(['auth:admin', 'permission:manage blog|manage blog comments,admin'])
    ->parameters(['comments' => 'blogComment'])
    ->names('admin.blog-comments');
Route::get('/admin/blog/page-settings', [BlogPageController::class, 'index'])
    ->middleware(['auth:admin', 'permission:manage blog|manage blog page settings,admin'])
    ->name('admin.blog-pages.index');
Route::get('/admin/blog/page-settings/edit', [BlogPageController::class, 'edit'])
    ->middleware(['auth:admin', 'permission:manage blog|manage blog page settings,admin'])
    ->name('admin.blog-pages.edit');
Route::put('/admin/blog/page-settings', [BlogPageController::class, 'update'])
    ->middleware(['auth:admin', 'permission:manage blog|manage blog page settings,admin'])
    ->name('admin.blog-pages.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
