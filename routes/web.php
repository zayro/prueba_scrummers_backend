<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


/**
 * TESTING
 */


$router->get('/', function () use ($router) {
    return $router->app->version();
});


/**
 * DEVELOPMENT
 */

if (app()->environment('local')) {
    $router->group(['prefix' => 'dev'], function () {
        Route::get('/{db}/all/{table}', 'GeneralController@all');
        Route::get('/{db}/field/{table}/', 'GeneralController@field');
        Route::get('/{db}/filters/{table}/{field}/{condition}', 'GeneralController@filter');
        Route::get('/{db}/filter/{table}/', 'GeneralController@filter');

        Route::post('/{db}/select', 'GeneralController@select');
        Route::post('/{db}/upload', 'GeneralController@upload');
        Route::post('/{db}/uploadInsert', 'GeneralController@uploadInsert');


        Route::post('/{db}/create/', 'GeneralController@create');
        Route::post('/{db}/createAutoincrement/', 'GeneralController@create_autoincrement');

        Route::put('/{db}/edit/', 'GeneralController@edit');
        Route::post('/{db}/destroy/', 'GeneralController@destroy');
    });
}


if (app()->environment('local')) {
    $router->group(['prefix' => 'api'], function () {
        Route::get('/room/availability', 'HotelController@view_reservation');
        Route::get('/room/availability', 'HotelController@view_reservation');
        Route::get('/room/list', 'HotelController@room');
        Route::get('/room/reservation_filter', 'HotelController@reservation_filter');
        Route::post('/room/reservation', 'HotelController@create');
        Route::put('/room/reservation', 'HotelController@edit');
        Route::delete('/room/reservation', 'HotelController@destroy');


    });
}



