<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::resource('news','NewsController')->middleware('auth');
Route::resource('contacts','ContactsController')->middleware('auth');

Auth::routes();