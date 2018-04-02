<?php
Route::group(['namespace' => 'Hannoma\Larapolls\Controllers', 'prefix' => config('larapolls.routes.home'), 'middleware' => 'web'], function(){
    Route::get('/', ['uses' => 'PollController@home', 'as' => 'larapolls.home']);
    Route::get('/category/{category}', ['uses' => 'PollController@home', 'as' => 'larapolls.category']);
    Route::post('/vote', ['uses' => 'PollController@vote', 'as' => 'larapolls.vote']);
    Route::group(['middleware' => config('larapolls.authMiddleware'), ['permission:'. config('larapolls.permissions.prefix') . config('larapolls.permissions.createPoll')]], function () {
      Route::get('/create/{category?}', ['uses' => 'PollController@showCreatePoll', 'as'=> 'larapolls.create']);
      Route::post('/create', ['uses' => 'PollController@postCreatePoll', 'as' => 'larapolls.create']);
    });
    Route::group(['middleware' => config('larapolls.authMiddleware')], function () {
      Route::post('/delete', ['uses' => 'PollController@deletePoll', 'as' => 'larapolls.delete']);
    });
    Route::group(['middleware' => config('larapolls.authMiddleware')], function () {
      Route::post('/allow', ['uses' => 'PollController@allowPoll', 'as' => 'larapolls.allow']);
    });
});
