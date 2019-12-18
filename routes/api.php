<?php

use Illuminate\Http\Request;

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


Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::name('verify')->get('users/verfiy/{token}', 'User\UserController@verfiy');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::resource('categories', 'Sistema\CategoryController', ['except' => ['create', 'edit']]);