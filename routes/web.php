<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->middleware('guest');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Password Confirm Routes...
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');    

// Email Verify Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

/*
	RUTAS PARA LA CEACION DE TOKENS PARA LAS APIS
*/
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/my-tokens', 'HomeController@getTokens')->name('personal-tokens');
Route::get('/home/my-clients', 'HomeController@getClients')->name('clients');
Route::get('/home/my-authorized-clients', 'HomeController@getAuthorizedClients')->name('authorized-clients');Auth::routes();

/*
	MIS RUTAS DEL SISTEMA
*/
Route::resource('departament', 'Sistema\DepartamentController');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
