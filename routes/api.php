<?php

// use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API (JWT)

Route::group(['middleware' => ['api']], function() {

    Route::post('/auth/signup', 'AuthController@signup');
    Route::post('/auth/signin', 'AuthController@signin');
    
    Route::get('/tutorial', 'TutorialController@index');
    Route::get('/tutorial/{id}', 'TutorialController@show');

    Route::group(['middleware' => ['jwt.auth']], function() {

        Route::get('/profile', 'UserController@show');
        
        Route::post('/tutorial', 'TutorialController@store');
        Route::put('/tutorial/{id}', 'TutorialController@update');
        Route::delete('/tutorial/{id}', 'TutorialController@destroy');

        Route::post('/comment/{tutorial_id}', 'CommentController@store');
    });

});
