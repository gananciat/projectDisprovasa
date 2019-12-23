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
Route::resource('products', 'Sistema\ProductController', ['except' => ['edit']]);
Route::resource('products.prices', 'Sistema\ProductPriceController', ['except' => ['edit']]);
Route::resource('schools', 'Sistema\SchoolController', ['except' => ['create', 'edit']]);
Route::resource('years', 'Sistema\YearController', ['except' => ['create', 'edit', 'store', 'update', 'destroy']]);

//New Route Usuario
Route::resource('users', 'Usuario\UserController', ['except' => ['edit']]);
