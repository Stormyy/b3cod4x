<?php
Route::group(['namespace' => 'Stormyy\B3\Http'], function(){
    Route::group(['middleware' => ['web']], function(){
        Route::get('/b3', 'B3Controller@getList');
        Route::get('/b3/{id}/players', 'B3Controller@getScreenshotList');
        Route::get('/b3/{id}/search/{query?}', 'B3Controller@getSearch');
        Route::get('/b3/{id}/player/{playerid}', 'B3Controller@getPlayer');
        Route::get('/b3/{id}/player', 'B3Controller@getCurrentPlayers');

        Route::group(['middleware' => 'auth'], function(){
            Route::get('/b3/add', 'B3Controller@getAdd');
            Route::post('/b3/{serverid}/save', 'B3Controller@postSave');
            Route::get('/b3/{serverid}/edit', 'B3Controller@getEdit');
        });
    });
    Route::post('/b3/screenshot', 'B3Controller@postScreenshot');
    Route::group(['middleware' => 'api'], function() {

        Route::group(['middleware' => 'auth:api'], function() {
            Route::post('/b3/{serverid}/screenshot/api', 'B3Controller@postScreenshotAPI');
        });

    });
});
