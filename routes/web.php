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
    return view('index');
});

Route::get('/crawler', 'CrawlController@crawlerAction');
Route::get('/link', 'CrawlController@frequencylink');
Route::get('/number', 'CrawlController@numbers');
Route::get('/date', 'CrawlController@dates');
Route::get('/call', 'CrawlController@call');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
