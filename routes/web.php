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
Route::get('/tables/{id}', 'TablesController@clientIndex');
Route::get('/tables/get', 'TablesController@get');
Route::post('/tables', 'TablesController@create');
Route::patch('/tables', 'TablesController@update')->middleware('is_admin');

Route::delete('/tables', 'TablesController@destroy')->middleware('is_admin');

// Table
Route::post('/table/{id}', 'TableController@updateData');
Route::get('/table/{id}', 'TableController@index');
Route::post('/table', 'TableController@add');
Route::patch('/table/{id}', 'TableController@update');
Route::delete('/table', 'TableController@empty');

Route::get('/orders/{id}', 'TableController@orders');
Route::delete('/orders', 'TableController@destroy');
Route::patch('/ordersamount', 'TableController@updateOrderAmount');
Route::patch('/ordersaddpercent', 'TableController@updateOrderAddpercent');

// Forniture
Route::get('/menu', 'FoodController@index')->name('menu');
Route::post('/menu', 'FoodController@create');
Route::post('/menu/search', 'FoodController@search');
Route::delete('/menu', 'FoodController@destroy');
Route::patch('/menu', 'FoodController@edit');

// Categories
Route::get('/categories', 'CategoriesController@index')->name('cats', 'secs');
Route::post('/categories', 'CategoriesController@store');
Route::post('/categories/create_section', 'CategoriesController@store');
Route::delete('/categories', 'CategoriesController@destroy');

// Clients
Route::get('/clients', 'ClientController@index');
Route::post('/clients', 'ClientController@create');
Route::delete('clients', 'ClientController@destroy');

// Users
Route::get('/users', 'AdminController@users')->name('users');
Route::post('/users', 'AdminController@create')->name('users');
Route::delete('/users', 'AdminController@destroy')->name('users');
Route::patch('/users', 'AdminController@editUser')->name('users');

// PDF Generation
Route::get('/pdf_view/{id}', 'PrintController@index')->name("datat");
Route::post('/pdf_view/{id}', 'PrintController@printpdf');

