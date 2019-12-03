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

Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionsController', ['only' => ['index']]);
Route::resource('buyers.products', 'Buyer\BuyerProductsController', ['only' => ['index']]);
Route::resource('buyers.sellers', 'Buyer\BuyerSellersController', ['only' => ['index']]);
Route::resource('buyers.categories', 'Buyer\BuyerCategoriesController', ['only' => ['index']]);
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('categories.products', 'Category\CategoryProductsController', ['only' => ['index']]);
Route::resource('categories.sellers', 'Category\CategorySellersController', ['only' => ['index']]);
Route::resource('categories.transactions', 'Category\CategoryTransactionsController', ['only' => ['index']]);
Route::resource('categories.buyers', 'Category\CategoryBuyersController', ['only' => ['index']]);
Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);
Route::resource('sellers.transactions', 'Seller\SellerTransactionsController', ['only' => ['index']]);
Route::resource('sellers.categories', 'Seller\SellerCategoriesController', ['only' => ['index']]);
Route::resource('sellers.buyers', 'Seller\SellerBuyersController', ['only' => ['index']]);
Route::resource('sellers.products', 'Seller\SellerProductsController', ['except' => ['create', 'show', 'edit']]);
Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);
Route::resource('products.transactions', 'Product\ProductTransactionsController', ['only' => ['index']]);
Route::resource('products.buyers', 'Product\ProductBuyersController', ['only' => ['index','store']]);
Route::resource('products.categories', 'Product\ProductCategoriesController', ['only' => ['index']]);
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('transactions.categories', 'Transaction\TransactionCategoriesController', ['only' => ['index']]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellersController', ['only' => ['index']]);

Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::name('verify')->get('users/verfiy/{token}', 'User\UserController@verfiy');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');