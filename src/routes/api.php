<?php
use Stormyy\B3\Models\B3Server;

Route::model('b3server', B3Server::class);
Route::group(['namespace' => 'Stormyy\B3\Http'], function () {
    Route::group(['middleware' => ['api']], function () {
        Route::post('/b3/screenshot', 'B3ServerController@postScreenshot');
    });
});