<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::post('login','StudentController@debug');
Route::post('logout','StudentController@debug');

Route::group(['prefix'=>'student'],function(){
    Route::get('grades','StudentController@debug');
    Route::get('courses','StudentController@debug');
    Route::post('enroll/{session_id}','StudentController@debug');
    Route::group(['prefix'=>'course'],function(){
        Route::get('{session_id}','StudentController@debug');
        Route::put('{session_id}/upload','StudentController@debug');
    });
});

Route::group(['prefix'=>'faculty'],function(){
    Route::get('course/{course_id}','StudentController@debug');
    Route::get('grades/{student_id}','StudentController@debug');
    Route::get('sessions','StudentController@debug');
    Route::group(['prefix'=>'session'],function(){
        Route::get('{session_id}/assignments','StudentController@debug');
        Route::put('{session_id}/upload','StudentController@debug');
    });
});

Route::group(['prefix'=>'admin'],function(){
    Route::get('courses','StudentController@debug');
    Route::group(['prefix'=>'course/{course_id}'],function(){
        Route::get('/','StudentController@debug');
        Route::post('modify','StudentController@debug');
        Route::put('add','StudentController@debug');
        Route::delete('delete','StudentController@debug');
    });
    Route::group(['prefix'=>'session/{session_id}'],function(){
        Route::put('add','StudentController@debug');
        Route::delete('delete','StudentController@debug');
    });
});