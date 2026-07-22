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
use App\Http\Controllers\Admin\CityController;
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
use App\Http\Controllers\Admin\SubscriptionPackageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AgentController;
use App\Http\Controllers\Frontend\AppointmentController;
use App\Http\Controllers\Frontend\BuyController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\EarlyAccessController;
use App\Http\Controllers\Frontend\FavoriteController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OpenHouseController;
use App\Http\Controllers\Frontend\PropertyDirectoryController;
use App\Http\Controllers\Frontend\RentController;
use App\Http\Controllers\Frontend\SellController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PropertyController as UserPropertyController;
use App\Http\Controllers\User\UserController as FrontendUserController;
use Illuminate\Http\Request;
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

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/for-sale/', [BuyController::class, 'index'])->name('frontend.buy.index');
Route::get('/for-rent/', [RentController::class, 'index'])->name('frontend.rent.index');
Route::get('/sell/', [SellController::class, 'index'])->name('frontend.sell.index');
Route::get('/real-estate-agents/', [AgentController::class, 'index'])->name('frontend.agents.index');
Route::get('/open-houses/', [OpenHouseController::class, 'index'])->name('frontend.open-houses.index');
Route::get('/early-access/', [EarlyAccessController::class, 'index'])->name('frontend.early-access.index');
Route::get('/blog/', [BlogController::class, 'index'])->name('frontend.blog.index');
Route::get('/blog/{slug}/', [BlogController::class, 'show'])->name('frontend.blog.show');
Route::get('/property/for-sale/', [PropertyDirectoryController::class, 'buySearch'])
    ->name('frontend.property.buy-search');
Route::get('/property/for-sale/residential/land-plot/{city}/', [PropertyDirectoryController::class, 'landSaleCity'])
    ->name('frontend.property.land-sale-city');
Route::get('/property/{purpose}/{type}/{district}/{city}/{localArea}/', [PropertyDirectoryController::class, 'purposeTypeLocalArea'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('frontend.property.purpose-type-local-area');
Route::get('/property/{purpose}/{type}/{district}/{city}/', [PropertyDirectoryController::class, 'purposeTypeCity'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('frontend.property.purpose-type-city');
Route::get('/property/{purpose}/{type}/{district}/', [PropertyDirectoryController::class, 'purposeTypeDistrict'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('type', '(?!residential$|commercial$|land$|industrial$)[A-Za-z0-9-]+')
    ->name('frontend.property.purpose-type-district');
Route::get('/property/{purpose}/{category}/{type}/', [PropertyDirectoryController::class, 'type'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('category', 'residential|commercial|land|industrial')
    ->name('frontend.property.type');
Route::get('/property/{purpose}/{category}/', [PropertyDirectoryController::class, 'category'])
    ->where('purpose', 'for-sale|for-rent|sell')
    ->where('category', 'residential|commercial|land|industrial')
    ->name('frontend.property.category');
Route::get('/property/{property}/', [PropertyDirectoryController::class, 'show'])
    ->where('property', '.+-[0-9]+')
    ->name('frontend.property.show');
Route::get('/property/{district}/{city}/{localArea}/', [PropertyDirectoryController::class, 'localArea'])
    ->name('frontend.property.local-area');
Route::get('/property/{district}/{city}/', [PropertyDirectoryController::class, 'city'])
    ->name('frontend.property.city');
Route::get('/property/{district}/', [PropertyDirectoryController::class, 'district'])
    ->name('frontend.property.district');
Route::post('/favorites/{property}/toggle', [FavoriteController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('frontend.favorites.toggle');
Route::post('/property/{property}/tour-request', [AppointmentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('frontend.property.tour-request');

Route::get('/dashboard', [FrontendUserController::class, 'profile'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/dashboard/account/', [FrontendUserController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('user.profile.edit');
Route::put('/dashboard/account/', [FrontendUserController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('user.profile.update');
Route::get('/dashboard/subscription/', [FrontendUserController::class, 'subscriptions'])
    ->middleware(['auth', 'verified'])
    ->name('user.subscriptions.index');
Route::post('/dashboard/subscription/{package}/checkout/', [FrontendUserController::class, 'checkout'])
    ->middleware(['auth', 'verified'])
    ->name('user.subscriptions.checkout');
Route::post('/dashboard/subscription/payment/success/', [FrontendUserController::class, 'paymentSuccess'])
    ->name('user.subscriptions.payment.success');
Route::post('/dashboard/subscription/payment/fail/', [FrontendUserController::class, 'paymentFail'])
    ->name('user.subscriptions.payment.fail');
Route::post('/dashboard/subscription/payment/cancel/', [FrontendUserController::class, 'paymentCancel'])
    ->name('user.subscriptions.payment.cancel');
Route::post('/dashboard/subscription/payment/ipn/', [FrontendUserController::class, 'paymentIpn'])
    ->name('user.subscriptions.payment.ipn');
Route::get('/dashboard/billings/', [FrontendUserController::class, 'billings'])
    ->middleware(['auth', 'verified'])
    ->name('user.billings.index');
Route::get('/dashboard/billings/add-payment', [FrontendUserController::class, 'addPayment'])
    ->middleware(['auth', 'verified'])
    ->name('user.billings.add-payment');
Route::get('/dashboard/activity-logs', [FrontendUserController::class, 'activityLogs'])
    ->middleware(['auth', 'verified'])
    ->name('user.activity-logs.index');
Route::get('/dashboard/appointments/', [FrontendUserController::class, 'appointments'])
    ->middleware(['auth', 'verified'])
    ->name('user.appointments.index');
Route::get('/dashboard/favorites/', [FrontendUserController::class, 'favorites'])
    ->middleware(['auth', 'verified'])
    ->name('user.favorites.index');
Route::get('/dashboard/saved-searches/', [FrontendUserController::class, 'savedSearches'])
    ->middleware(['auth', 'verified'])
    ->name('user.saved-searches.index');
Route::get('/dashboard/notifications/', [FrontendUserController::class, 'notifications'])
    ->middleware(['auth', 'verified'])
    ->name('user.notifications.index');
Route::get('/dashboard/open-house/', [FrontendUserController::class, 'openHouse'])
    ->middleware(['auth', 'verified'])
    ->name('user.open-house.index');
Route::get('/dashboard/feed/', [FrontendUserController::class, 'feed'])
    ->middleware(['auth', 'verified'])
    ->name('user.feed.index');
Route::get('/dashboard/properties/add-property', [UserPropertyController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('user.properties.create');
Route::post('/dashboard/properties/add-property', [UserPropertyController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('user.properties.store');
Route::get('/dashboard/properties/', [UserPropertyController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.properties.index');
Route::get('/dashboard/properties/active-properties', fn (Request $request, UserPropertyController $controller) => $controller->index($request, 'active'))
    ->middleware(['auth', 'verified'])
    ->name('user.properties.active');
Route::get('/dashboard/properties/pending-properties', fn (Request $request, UserPropertyController $controller) => $controller->index($request, 'pending'))
    ->middleware(['auth', 'verified'])
    ->name('user.properties.pending');
Route::get('/dashboard/properties/rejected-properties', fn (Request $request, UserPropertyController $controller) => $controller->index($request, 'rejected'))
    ->middleware(['auth', 'verified'])
    ->name('user.properties.rejected');
Route::get('/dashboard/properties/expired-properties', fn (Request $request, UserPropertyController $controller) => $controller->index($request, 'expired'))
    ->middleware(['auth', 'verified'])
    ->name('user.properties.expired');
Route::get('/user/dashboard', [FrontendUserController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard');

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

Route::resource('/admin/subscription-packages', SubscriptionPackageController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage subscriptions,admin'])
    ->names('admin.subscription-packages');

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
Route::resource('/admin/cities', CityController::class)
    ->except('show')
    ->middleware(['auth:admin', 'permission:manage locations,admin'])
    ->names('admin.cities');
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
