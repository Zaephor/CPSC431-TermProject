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

Route::post('login','TestController@debug');
Route::post('logout','TestController@debug');

Route::group(['prefix'=>'student'],function(){
    Route::get('grades','StudentController@getGrades');
    Route::get('courses','StudentController@getCourses');
    Route::post('enroll/{session_id}','TestController@debug');
    Route::group(['prefix'=>'course'],function(){
        Route::get('{session_id}','TestController@debug');
        Route::put('{session_id}/upload','TestController@debug');
    });
});

Route::group(['prefix'=>'faculty'],function(){
    Route::get('course/{course_id}','FacultyController@getCourse');
    Route::get('grades/{student_id}','FacultyController@getGrades');
    Route::get('sessions','FacultyController@getSessions');
    Route::group(['prefix'=>'session'],function(){
        Route::get('{session_id}/assignments','TestController@debug');
        Route::put('{session_id}/upload','TestController@debug');
    });
});

Route::group(['prefix'=>'admin'],function(){
    Route::get('courses','AdminController@getCourses');
    Route::group(['prefix'=>'course/{course_id}'],function(){
        Route::get('/','AdminController@getCourseInfo');
        Route::post('modify','TestController@debug');
        Route::put('add','TestController@debug');
        Route::delete('delete','TestController@debug');
    });
    Route::group(['prefix'=>'session/{session_id}'],function(){
        Route::put('add','TestController@debug');
        Route::delete('delete','TestController@debug');
    });
});