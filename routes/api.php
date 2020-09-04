<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login','UserController@login')->name('login');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('register','UserController@register');
        Route::ApiResource('directorios','DirectorioController');
        Route::post('logout','UserController@logout');
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });
});

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
