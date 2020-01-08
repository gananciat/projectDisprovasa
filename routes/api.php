<?php

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

Route::name('login')->post('auth/login', 'Usuario\AuthController@login');
Route::name('logout')->get('auth/logout', 'Usuario\AuthController@logout');
Route::name('me')->get('auth/me', 'Usuario\AuthController@user');
Route::name('me_eschool')->get('auth/me_school', 'Usuario\AuthController@userEschool');

Route::name('login_escuela')->post('auth/login_escuela', 'Usuario\AuthController@loginEscuela');


//Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::name('verify')->get('users/verfiy/{token}', 'User\UserController@verfiy');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::resource('categories', 'Sistema\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('companies', 'Sistema\CompanyController', ['except' => ['create', 'edit']]);
Route::resource('months', 'Sistema\MonthController', ['except' => ['create', 'edit', 'store', 'show', 'update', 'destroy']]);
Route::resource('municipalities', 'Sistema\MunicipalityController', ['except' => ['create', 'edit', 'store', 'show', 'update', 'destroy']]);
Route::resource('person_schools', 'Sistema\PersonSchoolController', ['except' => ['index', 'create', 'show', 'edit']]);
Route::resource('phone_people', 'Sistema\PhonePersonController', ['except' => ['index', 'create', 'show', 'edit', 'update']]);
Route::resource('phone_schools', 'Sistema\PhoneSchoolController', ['except' => ['index', 'create', 'show', 'edit', 'update']]);
Route::resource('presentations', 'Sistema\PresentationController', ['except' => ['create', 'edit']]);
Route::resource('prices', 'Sistema\PriceController', ['except' => ['index', 'create', 'edit', 'update']]);
Route::resource('products', 'Sistema\ProductController', ['except' => ['create']]);
Route::resource('providers', 'Sistema\ProviderController', ['except' => ['create']]);
Route::name('providers_show_by_nit')->get('providers_show_by_nit/{nit}', 'Sistema\providerController@showByNit');

Route::resource('products.prices', 'Sistema\ProductPriceController', ['except' => ['edit']]);
Route::resource('schools', 'Sistema\SchoolController', ['except' => ['create', 'edit']]);
Route::resource('school_presidents', 'Sistema\SchoolPresidentController', ['except' => ['index', 'create', 'edit', 'update']]);
Route::resource('years', 'Sistema\YearController', ['except' => ['create', 'edit', 'store', 'update', 'destroy']]);
Route::resource('reservations', 'Sistema\ReservationController', ['except' => ['index' ,'create', 'edit', 'update', 'destroy']]);
Route::resource('orders', 'Sistema\OrderController', ['except' => ['create', 'edit']]);
Route::resource('detail_orders', 'Sistema\DetailOrderController', ['except' => ['index', 'create', 'edit']]);
Route::resource('purchases', 'Sistema\PurchaseController', ['except' => ['create', 'edit']]);
Route::resource('calendar_school', 'Sistema\CalendarSchoolController', ['except' => ['index', 'create', 'edit', 'update']]);
Route::resource('progress_orders', 'Sistema\ProgressOrderController', ['except' => ['index', 'create', 'store', 'destroy']]);
//New Route Usuario
Route::resource('users', 'Usuario\UserController', ['except' => ['edit']]);
Route::resource('disbursements', 'Sistema\DisbursementController', ['except' => ['edit']]);
Route::resource('balances', 'Sistema\BalanceController', ['except' => ['edit']]);
Route::resource('schools.balances', 'Sistema\SchoolBalanceController', ['except' => ['edit']]);
Route::name('schools_show')->get('schools_show/{id}', 'Sistema\schoolController@getOne');
Route::resource('notifications', 'Reports\NotificationController', ['except' => ['edit']]);
