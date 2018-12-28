<?php
use Stormyy\B3\Models\B3Server;

Route::model('b3server', B3Server::class);
Route::group(['namespace' => 'Stormyy\B3\Http'], function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get('/b3/list', 'B3ServerController@getList');
        Route::get('/b3/{b3server}', 'B3ServerController@get');
        Route::get('/b3/{b3server}/player/{playerid}', 'B3PlayerController@get');
        Route::get('/b3/{b3server}/player', 'B3ServerController@getPlayers');
        Route::get('/b3/{b3server}/admins', 'B3ServerController@getAdmins');
        Route::get('/b3/{b3server}/bans', 'B3ServerController@getActiveBans');
        Route::get('/b3/{b3server}/permissions', 'B3ServerController@getPermissions');
        Route::get('/b3/{b3server}/penalty/{penaltyid}/screenshot', 'B3ServerController@getScreenshotByPenalty');

        Route::group(['middleware' => 'auth'], function () {
            Route::group(['middleware' => ['can:manage,'.B3Server::class]], function(){
                Route::get('/b3/add', 'B3ServerController@getAdd');
                Route::post('/b3/{serverid}/save', 'B3ServerController@postSave');
                Route::get('/b3/{serverid}/edit', 'B3ServerController@getEdit');
            });

            Route::get('/b3/claim', 'B3PlayerController@getClaim');

            Route::get('/b3/{b3server}/unban/{penaltyid}', 'B3PlayerController@getRemovePenalty');
            Route::get('/b3/profile', 'B3PlayerController@getProfile');

            Route::group(['middleware' => ['can:chat,b3server']], function () {
                Route::post('/b3/{b3server}/chat', 'B3ServerController@postChat');
            });

            Route::post('/b3/{b3server}/{guid}/rank', 'B3PlayerController@postRank');

            Route::group(['middleware' => ['can:screenshot,b3server']], function () {
                Route::post('/b3/{b3server}/screenshot/api', 'B3ServerController@postScreenshotAPI');
                Route::post('/b3/{b3server}/ban/{guid}', 'B3PlayerController@postBan');
            });
        });
    });
});
