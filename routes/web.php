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

// Route::get('/', function () {
//     return view('welcome');
// });


//Route::get('/', 'SnackyStatsController@index');

Route::get('/', array('as'=>'index', 'uses'=>'SnackyStatsController@index'));
Route::get('/getData', array('as'=>'getData', 'uses'=>'SnackyStatsController@getAllData'));

Route::get('/getDataToday', array('as'=>'getDataToday', 'uses'=>'SnackyStatsController@getDataToday'));
Route::get('/today', array('as'=>'today', 'uses'=>'SnackyStatsController@getTodayPage'));

Route::get('/getDataYesterday', array('as'=>'getDataYesterday', 'uses'=>'SnackyStatsController@getDataYesterday'));
Route::get('/yesterday', array('as'=>'yesterday', 'uses'=>'SnackyStatsController@getYesterdayPage'));


Route::get('/lastsevendays', array('as'=>'lastsevendays', 'uses'=>'SnackyStatsController@LastsevenDaysPage'));
Route::get('/lastsevendaysData', array('as'=>'lastsevendaysData', 'uses'=>'SnackyStatsController@LastsevenDaysData'));

Route::get('/last30days', array('as'=>'last30days', 'uses'=>'SnackyStatsController@last30days'));
Route::get('/thismonth', array('as'=>'thismonth', 'uses'=>'SnackyStatsController@thismonth'));
Route::get('/lastmonth', array('as'=>'lastmonth', 'uses'=>'SnackyStatsController@lastmonth'));



Route::get('/checkOpenOrClosed', array('as'=>'checkOpenOrClosed', 'uses'=>'SnackyStatsController@checkOpenOrClosed'));
Route::post('/postGetData', array('as'=>'postGetData', 'uses'=>'SnackyStatsController@postGetData'));