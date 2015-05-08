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

        Route::get('courses', 'StudentController@getCourses'); //Return all user's courses
        Route::get('courses/all','TestController@debug');//TODO return all courses in system
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'StudentController@getCourseSessions'); //Return full course->session object
        });
        Route::get('sessions','TestController@debug');//TODO return all user's enrolled sessions
        Route::get('sessions/all','TestController@debug');//TODO get all available sessions(grouped by course)
        Route::group(['prefix' => 'session'], function () {
            Route::put('{session_id}/upload', 'StudentController@postCourseSessionUpload');
        });
        Route::get('assignments', 'StudentController@getGrades'); //Get all student's grades, for all sessions, for all courses
        Route::group(['prefix'=>'assignment'],function(){
            Route::post('{assignment_id}','StudentController@displayAssignment');
            Route::put('{assignment_id}/upload','TestController@debug');
        });
    });

    Route::group(['prefix' => 'faculty'], function () {
        Route::get('courses','FacultyController@getCourses'); //TODO Return all courses tied to this prof
        Route::get('courses/all','TestController@debug');//TODO return all courses in system
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'FacultyController@getCourseInfo'); //Returns course object of specific course with sessions
        });
        Route::get('sessions', 'FacultyController@getSessions'); //Returns all sessions professor is responsible for
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}/assignments', 'FacultyController@getSessionAssignments');//TODO get all assignments for this session
        });
        Route::get('grades/{student_id}', 'FacultyController@getGrades'); //Returns all grades for a given student,professor pair
        Route::get();//TODO returns course objects, containing sessions, which will contain all assignments? still thinking
        Route::group(['prefix'=>'assignment'],function(){
            Route::get('{assignment_id}','TestController@debug');//TODO Display assignment object
            Route::post('add','TestController@debug');//TODO Create an assignment for a class, assume sessionID in post data
            Route::put('{assignment_id}/modify','TestController@debug');//TODO Update assignment object(IE set grade)
            Route::delete('{assignment_id}/delete','TestController@debug');//TODO Delete the assignment
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