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

Route::get('/tasks','TaskController@index');
Route::post('/tasks','TaskController@store');
Route::put('/tasks/{task}','TaskController@update');
Route::patch('/tasksCheckAll','TaskController@updateAll');
Route::delete('/tasks/{task}','TaskController@destroy');
Route::delete('/tasksDeleteCompleted','TaskController@destroyCompleted');

