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
    Route::post('register', 'UserController@postRegister');
    Route::post('login', 'UserController@postLogin');
    Route::post('logout', 'UserController@postLogout');

    Route::group(['prefix' => 'student'], function () {
        Route::get('grades', 'StudentController@getGrades'); //Get all student's grades, for all sessions, for all courses
        Route::get('courses', 'StudentController@getCourses'); //Get all student's courses
        Route::post('enroll/{session_id}', 'StudentController@postEnroll'); //TODO[GRABUSERIDFROMTOKEN] Enroll student in course session
        Route::group(['prefix' => 'course'], function () {
            Route::get('{session_id}', 'StudentController@getCourseSession');
            Route::put('{session_id}/upload', 'StudentController@postCourseSessionUpload');
        });
    });

    Route::group(['prefix' => 'faculty'], function () {
        Route::get('course/{course_id}', 'FacultyController@getCourse'); //Returns course object of specific course[Duplicate of Admin Version]
        Route::get('grades/{student_id}', 'FacultyController@getGrades'); //Returns all grades for a given student
        Route::get('sessions', 'FacultyController@getSessions'); //TODO[FINISH] Returns all sessions professor is responsible for
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}/assignments', 'FacultyController@getSessionAssignments');
            Route::put('{session_id}/upload', 'FacultyController@postSessionUpload');
        });
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('courses', 'AdminController@getCourses'); //Returns all of them, plus their department
        Route::group(['prefix' => 'course'], function () {
            Route::put('add', 'AdminController@putCourseAdd'); //Create new course, returns status response and data object
            Route::get('{course_id}', 'AdminController@getCourseInfo'); //Returns specific match, plus department and session info
            Route::post('{course_id}/modify', 'AdminController@postCourseModify'); //TODO
            Route::delete('{course_id}/delete', 'AdminController@deleteCourseDelete'); //Deletes specific match
        });
        Route::group(['prefix' => 'session'], function () {
            Route::put('add', 'AdminController@putSessionAdd'); //Create new course session, return status response and data object
            Route::get('{session_id}', 'AdminController@getSessionInfo'); //Returns matching session object, with all relevant data
            Route::post('{session_id}/modify', 'AdminController@postSessionModify'); //TODO
            Route::delete('{session_id}/delete', 'AdminController@deleteSessionDelete'); //Deletes specific match
        });
    });
});