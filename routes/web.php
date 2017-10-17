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

Route::get('/admin/books', 'BooksController@index')
    ->middleware('admin')
    ->name('admin-books');
Route::get('/admin/users', 'UsersController@index')
    ->middleware('admin')
    ->name('admin-users');
Route::get('/admin/user/edit/{user_id}', 'UsersController@edit')
       ->middleware('admin')
       ->name('edit-user');

Route::post('/user/information/update', 'DashboardController@updateInfo')
       ->name('update-info');
Route::post('/admin/user/edit/{user_id}', 'UsersController@update')
       ->middleware('admin')
       ->name('update-user');

/** Auth routes **/
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/password/email',
            'Auth\ForgotPasswordController@sendResetLinkEmail')
       ->name('password.email');
Route::get('/password/reset',
           'Auth\ForgotPasswordController@showLinkRequestForm')
       ->name('password.request');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
Route::get('/password/reset/{token}',
           'Auth\ResetPasswordController@showResetForm')
       ->name('password.reset');
Route::get('/register',
           function() {return Redirect::to(
              'https://www.openbookpublishers.com/customer.php?xCmd=register'
            );})
       ->name('register');
Route::post('/register', 'Auth\RegisterController@register');
