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
Route::group(['prefix'=>'v1'], function(){


	Route::post('login', 'Api\PassportController@login');
	Route::post('register', 'Api\PassportController@register');

	Route::group(['middleware'=>'auth:api'], function(){
		Route::post('user', 'Api\PassportController@getDetails');


		Route::post('settings', 'Api\SettingController@index');
		Route::post('settings/update', 'Api\SettingController@update');
		Route::post('profile/update', 'Api\SettingController@updateProfile');

		Route::get('invoices', 'Api\InvoiceController@index');
		Route::post('invoices', 'Api\InvoiceController@store');


	});
});

