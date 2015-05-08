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

/*
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
*/

Route::group(['prefix' => 'api'], function () {
    Route::post('register', 'UserController@postRegister');
    Route::post('login', 'UserController@postLogin'); //O.o This actually works, returns JWT token containing user object and other JWT claims
    Route::post('logout', 'UserController@postLogout');

    Route::group(['prefix' => 'student'], function () {
        Route::post('enroll/{session_id}', 'StudentController@postEnroll'); //TODO[TEST] Enroll student in course session

        Route::get('courses', 'StudentController@getCourses'); //Get all student's courses
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'StudentController@getCourseSessions'); // Get avail data for that course
        });
        Route::get('sessions','');
        Route::group(['prefix' => 'session'], function () {
            Route::put('{session_id}/upload', 'StudentController@postCourseSessionUpload');
        });
        Route::get('assignments', 'StudentController@getGrades'); //Get all student's grades, for all sessions, for all courses
        Route::group(['prefix'=>'assignment'],function(){
            Route::post('{assignment_id}','StudentController@displayAssignment');
            Route::put('{assignment_id}/upload','');
        });
    });

    Route::group(['prefix' => 'faculty'], function () {
        Route::get('courses','FacultyController@getCourses');
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'FacultyController@getCourseInfo'); //Returns course object of specific course with sessions
        });
        Route::get('sessions', 'FacultyController@getSessions'); //Returns all sessions professor is responsible for
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}/assignments', 'FacultyController@getSessionAssignments');
            Route::put('{session_id}/upload', 'FacultyController@postSessionUpload');
        });
        Route::get('grades/{student_id}', 'FacultyController@getGrades'); //Returns all grades for a given student,professor pair
        Route::group(['prefix'=>'assignment'],function(){

        });
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('courses', 'AdminController@getCourses'); //Returns all of them, plus their department
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'AdminController@getCourseInfo'); //Returns specific match, plus department and session info
            Route::put('add', 'AdminController@putCourseAdd'); //Create new course, returns status response and data object
            Route::post('{course_id}/modify', 'AdminController@postCourseModify'); //TODO
            Route::delete('{course_id}/delete', 'AdminController@deleteCourseDelete'); //Deletes specific match
        });
        Route::get('sessions','AdminController@getSessions'); //Returns all sessions, grouped by course
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}', 'AdminController@getSessionInfo'); //Returns matching session object, with all relevant data
            Route::put('add', 'AdminController@putSessionAdd'); //Create new course session, return status response and data object
            Route::post('{session_id}/modify', 'AdminController@postSessionModify'); //TODO
            Route::delete('{session_id}/delete', 'AdminController@deleteSessionDelete'); //Deletes specific match
        });
    });
});