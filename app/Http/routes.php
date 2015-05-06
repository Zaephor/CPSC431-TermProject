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


Route::group(['prefix' => 'api'], function () {
    Route::post('login', 'UserController@postLogin');
    Route::post('logout', 'UserController@postLogout');

    Route::group(['prefix' => 'student'], function () {
        Route::get('grades', 'StudentController@getGrades');
        Route::get('courses', 'StudentController@getCourses');
        Route::post('enroll/{session_id}', 'StudentController@postEnroll');
        Route::group(['prefix' => 'course'], function () {
            Route::get('{session_id}', 'StudentController@getCourseSession');
            Route::put('{session_id}/upload', 'StudentController@postCourseSessionUpload');
        });
    });

    Route::group(['prefix' => 'faculty'], function () {
        Route::get('course/{course_id}', 'FacultyController@getCourse');
        Route::get('grades/{student_id}', 'FacultyController@getGrades');
        Route::get('sessions', 'FacultyController@getSessions');
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}/assignments', 'FacultyController@getSessionAssignments');
            Route::put('{session_id}/upload', 'FacultyController@postSessionUpload');
        });
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('courses', 'AdminController@getCourses');
        Route::group(['prefix' => 'course/{course_id}'], function () {
            Route::get('/', 'AdminController@getCourseInfo');
            Route::post('modify', 'AdminController@postCourseModify');
            Route::put('add', 'AdminController@putCourseAdd');
            Route::delete('delete', 'AdminController@deleteCourseDelete');
        });
        Route::group(['prefix' => 'session/{session_id}'], function () {
            Route::put('add', 'AdminController@putSessionAdd');
            Route::delete('delete', 'AdminController@deleteSessionDelete');
        });
    });
});