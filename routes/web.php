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

Route::get('/', 'DashboardController@index');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/book/report/pdf/{book_id}', 'BooksController@downloadFullReport')
       ->name('download-report');
Route::get('/readership/graphs/{book_id}', 'BooksController@readershipGraphs')
       ->name('graphs');
Route::get('/readership/map/{book_id}', 'BooksController@readershipMap')
       ->name('map');

Route::post('/user/information/update', 'DashboardController@updateInfo')
       ->name('update-info');

Auth::routes();
