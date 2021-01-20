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

Route::get('/home', 'LoanController@index')->name('home');
Route::post('/home', 'LoanController@newLoan')->name('loan');
Route::get('/approve/{loanID}', 'LoanController@approveLoan')->name('loan.approve');
Route::post('/pay', 'LoanController@redirectToGateway')->name('pay');
Route::get('/payment/callback', 'LoanController@handleGatewayCallback');
