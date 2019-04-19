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


Route::get('/', array('as'=>'index', 'uses'=>'SnackyStatsController@index'));
Route::get('/getDataToday', array('as'=>'getDataToday', 'uses'=>'SnackyStatsController@getDataToday'));
Route::get('/getDataYesterday', array('as'=>'getDataYesterday', 'uses'=>'SnackyStatsController@getDataYesterday'));
Route::get('/lastsevendaysData', array('as'=>'lastsevendaysData', 'uses'=>'SnackyStatsController@LastsevenDaysData'));
Route::get('/last30days', array('as'=>'last30days', 'uses'=>'SnackyStatsController@last30days'));
Route::get('/thismonth', array('as'=>'thismonth', 'uses'=>'SnackyStatsController@thismonth'));
Route::get('/lastmonth', array('as'=>'lastmonth', 'uses'=>'SnackyStatsController@lastmonth'));
Route::get('/checkOpenOrClosed', array('as'=>'checkOpenOrClosed', 'uses'=>'SnackyStatsController@checkOpenOrClosed'));
