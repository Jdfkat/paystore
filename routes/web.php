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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::post('/paypal/notify', 'PaypalController@notify');
Route::post('/paypal/notify', 'TestControler@success');
Route::resource('items', 'ItemController');
Route::resource('/videos', 'VideoController');
Route::post('/serviceroute', 'TestControler@success');


