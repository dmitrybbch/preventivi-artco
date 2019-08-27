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
    return redirect('tables');
});

Auth::routes();

// Tables
Route::get('/tables', 'TablesController@index')->name('tables');
Route::get('/tables/get', 'TablesController@get');
Route::post('/tables', 'TablesController@create')->middleware('is_admin');
Route::patch('/tables', 'TablesController@update')->middleware('is_admin');
Route::delete('/tables', 'TablesController@destroy')->middleware('is_admin');

// Table
Route::get('/orders/{id}', 'TableController@orders');
Route::delete('/orders', 'TableController@destroy');
Route::get('/table/{id}', 'TableController@index');
Route::post('/table', 'TableController@add');
Route::patch('/table', 'TableController@update');
Route::delete('/table', 'TableController@empty');


// Food
Route::get('/menu', 'FoodController@index')->name('menu')->middleware('is_admin');
Route::post('/menu', 'FoodController@create')->middleware('is_admin');
Route::post('/menu/search', 'FoodController@search');
Route::delete('/menu', 'FoodController@destroy')->middleware('is_admin');
Route::patch('/menu', 'FoodController@edit')->middleware('is_admin');

// Users
Route::get('/users', 'AdminController@users')->name('users');
Route::post('/users', 'AdminController@create')->name('users');
Route::delete('/users', 'AdminController@destroy')->name('users');
Route::patch('/users', 'AdminController@editUser')->name('users');
