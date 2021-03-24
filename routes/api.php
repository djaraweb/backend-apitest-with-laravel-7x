<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login','UserController@login')->name('login');
    Route::post('register','UserController@register')->name('register');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout','UserController@logout')->name('logout');

        // Directorios
        Route::ApiResource('directorios','DirectorioController');
        // Tasks
        Route::get('/tasks','TaskController@index')->name('tasks.index');
        Route::post('/tasks','TaskController@store')->name('tasks.store');
        Route::put('/tasks/{task}','TaskController@update')->name('tasks.update');       
        Route::patch('/tasksCheckAll','TaskController@updateAll');
        Route::delete('/tasks/{task}','TaskController@destroy')->name('tasks.destroy');
        Route::delete('/tasksDeleteCompleted','TaskController@destroyCompleted');

        // Otros
        Route::get('user', function (Request $request) {
            return $request->user();
        });
    });
});

