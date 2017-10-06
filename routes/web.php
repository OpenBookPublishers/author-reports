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

Route::get('/', function() {return Redirect::to('dashboard');});
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/book/report/html/{book_id}', 'BooksController@fullReportHtml')
       ->name('report');
Route::get('/book/report/pdf/{book_id}', 'BooksController@downloadFullReport')
       ->name('download-report');
Route::get('/readership/graphs/{book_id}', 'BooksController@readershipGraphs')
       ->name('graphs');
Route::get('/readership/map/{book_id}', 'BooksController@readershipMap')
       ->name('map');

Route::get('/admin/books', 'BooksController@index')->name('admin-books');
Route::get('/admin/users', 'UsersController@index')->name('admin-users');

Route::post('/user/information/update', 'DashboardController@updateInfo')
       ->name('update-info');

Auth::routes();
