<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	Route::get('/', function () {
		return view('welcome');
	});	
	
	Route::auth();	
});

Route::group(['prefix' => 'api/v1/'], function () {
	Route::resource('items', 'ItemsController', ['except' => ['create', 'edit']]);
	Route::resource('orders', 'OrdersController', ['except' => ['create', 'edit']]);
	Route::resource('locations', 'LocationsController', ['except' => ['create', 'edit']]);
	Route::resource('vendors', 'VendorsController', ['except' => ['create', 'edit']]);	
	Route::resource('channels', 'ChannelsController', ['except' => ['create', 'edit']]);
	Route::get('sync', 'ChannelsController@sync');
});
	
