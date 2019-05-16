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

/* Setup CORS */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Api login
Route::post('login', 'UserManagementController@apiLogin')->name('login');

// Not logged in
Route::get('login', 'UserManagementController@apiLogin');

// Api logout
Route::middleware('auth:api')->post('logout', 'UserManagementController@apiLogout');

// List users
Route::middleware('auth:api')->get('users', 'UserManagementController@index');

// List single user
Route::middleware('auth:api')->get('user/{id}', 'UserManagementController@show');

// Create new user
Route::middleware('auth:api')->post('user/create', 'UserManagementController@create');

// Update user
Route::middleware('auth:api')->put('user/update/{id}', 'UserManagementController@update');

// Delete user
Route::middleware('auth:api')->delete('user/delete/{id}', 'UserManagementController@destroy');
