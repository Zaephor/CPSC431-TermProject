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

Route::group(['prefix' => 'api'], function () {
    Route::post('register', 'UserController@postRegister');
    Route::post('login', 'UserController@postLogin'); //O.o This actually works, returns JWT token containing user object and other JWT claims
    Route::post('logout', 'UserController@postLogout');

    Route::group(['prefix' => 'student'], function () {
        Route::post('enroll/{session_id}', 'StudentController@postEnroll'); //TODO[TEST] Enroll student in course session

        Route::get('courses', 'StudentController@getSessions'); //Return all user's courses and session subobjects
        Route::get('courses/all', 'AdminController@getCourses');//Just use Admin's get all courses function
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'StudentController@getCourseSessions'); //Return full course->session object for specific course
        });

        Route::get('sessions', 'StudentController@getSessions');//Return all user's enrolled sessions(grouped by course)
        Route::get('sessions/all', 'AdminController@getSessions');//Just use admin's get all function
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}', 'StudentController@getSpecificSession'); //Return session object matching that id
//            Route::get('{session_id}/assignments','StudentController@getSessionAssignments'); // get all specified session's assignments, ASSIGNMENTS ARE HANDLED ELSEWHERE
//            Route::put('{session_id}/upload', 'StudentController@postCourseSessionUpload'); //IS this really needed?
        });

        Route::get('assignments', 'StudentController@getAssignments'); //Get all student's grades, for all sessions, for all courses
        Route::group(['prefix' => 'assignment'], function () {
            Route::post('{assignment_id}', 'StudentController@displayAssignment'); //TODO view single assignment
            Route::put('{assignment_id}/upload', 'StudentController@putAssignment'); // TODO Write upload and storage logic
        });
    });

    Route::group(['prefix' => 'faculty'], function () {
        Route::get('grades/{student_id}', 'FacultyController@getGrades'); //Returns all grades for a given student,professor pair

        Route::get('courses', 'FacultyController@getCourses'); //TODO Return all courses tied to this prof
        Route::get('courses/all', 'AdminController@getCourses');//Just use Admin's get all courses function
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'FacultyController@getCourseInfo'); //Returns course object of specific course with sessions
        });

        Route::get('sessions', 'FacultyController@getSessions'); //Returns all sessions professor is responsible for
        Route::get('sessions/all', 'AdminController@getSessions'); //Just use admin's get all function
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}','StudentController@getSpecificSession'); // Stolen from students
            Route::get('{session_id}/assignments', 'FacultyController@getSessionAssignments');//TODO get all assignments for this session ??MAYBE?
            Route::get('{session_id}/students','FacultyController@getSessionStudents'); // Should return the session object with a students subobject
        });

//        Route::get();//TODO returns course objects, containing sessions, which will contain all assignments? still thinking
        Route::group(['prefix' => 'assignment'], function () {
            Route::get('{assignment_id}', 'FacultyController@getAssignment');//TODO Display assignment object
            Route::post('add', 'FacultyController@postCreateAssignment');//TODO Create an assignment for a class, assume sessionID in post data
            Route::put('{assignment_id}/modify', 'FacultyController@putModifyAssignment');//TODO Update assignment object(IE set grade)
            Route::delete('{assignment_id}/delete', 'FacultyController@deleteDeleteAssignment');//TODO Delete the assignment
        });
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::get('faculty/all','AdminController@getAllFactulty'); //Returns all faculty
        Route::get('courses', 'AdminController@getCourses'); //Returns all of them, plus their department
        Route::group(['prefix' => 'course'], function () {
            Route::get('{course_id}', 'AdminController@getCourseInfo'); //Returns specific match, plus department and session info
            Route::put('add', 'AdminController@putCourseAdd'); //Create new course, returns status response and data object
            Route::post('{course_id}/modify', 'AdminController@postCourseModify'); //[UNTESTED]
            Route::delete('{course_id}/delete', 'AdminController@deleteCourseDelete'); //Deletes specific match
        });
        Route::get('sessions', 'AdminController@getSessions'); //Returns all sessions, grouped by course
        Route::group(['prefix' => 'session'], function () {
            Route::get('{session_id}', 'AdminController@getSessionInfo'); //Returns matching session object, with all relevant data
            Route::put('add', 'AdminController@putSessionAdd'); //Create new course session, return status response and data object
            Route::post('{session_id}/modify', 'AdminController@postSessionModify'); //[UNTESTED]
            Route::delete('{session_id}/delete', 'AdminController@deleteSessionDelete'); //Deletes specific match
        });
    });
});