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
    phpinfo();
});

// 凯撒加密
Route::get('index','Controller\UserController@index');

// 对称加密
Route::get('encrypt','Controller\UserController@encrypt');

// 非对称加密
Route::get('enc','Controller\UserController@keyEncrypt');

// 签名
Route::get('sign','Controller\UserController@sign');