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
Route::get('/account', 'DashboardController@account')->name('account');
Route::get('/book/report/html/{book_id}/{year?}',
           'BooksController@fullReportHtml')
       ->name('report');
Route::get('/book/report/pdf/{book_id}/{year?}',
           'BooksController@downloadFullReport')
       ->name('download-report');
Route::get('/readership/graphs/{book_id}', 'BooksController@readershipGraphs')
       ->name('graphs');
Route::get('/readership/map/{book_id}', 'BooksController@readershipMap')
       ->name('map');

Route::get('/admin/books', 'BooksController@index')
    ->middleware('admin')
    ->name('admin-books');
Route::get('/admin/users', 'UsersController@index')
    ->middleware('admin')
    ->name('admin-users');
Route::get('/admin/user/edit/{user_id}', 'UsersController@edit')
       ->middleware('admin')
       ->name('edit-user');

Route::post('/account/update', 'DashboardController@updateAccount')
       ->name('update-account');
Route::post('/password/update', 'DashboardController@updatePassword')
       ->name('update-password');
Route::post('/user/information/update', 'DashboardController@updateInfo')
       ->name('update-info');
Route::post('/admin/user/edit/{user_id}', 'UsersController@update')
       ->middleware('admin')
       ->name('update-user');
Route::post('/admin/user/delete/{user_id}', 'UsersController@delete')
       ->middleware('admin')
       ->name('delete-user');

Auth::routes();
