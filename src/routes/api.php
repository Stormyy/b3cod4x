<?php
use Stormyy\B3\Models\B3Server;

Route::model('b3server', B3Server::class);
Route::group(['namespace' => 'Stormyy\B3\Http'], function () {
    Route::group(['middleware' => ['api']], function () {
        Route::get('/b3/{b3server}/chat', 'B3ServerController@getChat');


        Route::post('/b3/screenshot', 'B3ServerController@postScreenshot');
        Route::get('/b3/{b3server}/search/{query?}', 'B3ServerController@getSearch');
    });
});