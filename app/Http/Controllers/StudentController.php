<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 5:50 PM
 */

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\Assignment;
use App\Session;
use JWTAuth;

class StudentController extends Controller
{
    public function postEnroll($session_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $user = User::find($userAuth->id); // Get student ID from user token or session
        $user->sessions()->attach($session_id);
        $status = 200;
        return array('status' => $status);
    }

    public function getCourses()
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $user = User::find($userAuth->id);
        $status = 404;
        if (sizeof($user) > 0) {
            $status = 200;
            $user->load('sessions', 'sessions.course','sessions.course.department');
        }
        $rearrange = array();
        foreach ($user['sessions'] as $value) {
            $temp = $value['course'];
            $rearrange[] = $temp;
        }
        return array('status' => $status, 'data' => $rearrange);
    }

    public function getCourseSessions($course_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $course = Course::find($course_id);
        $status = 404;
        if (sizeof($course) > 0) {
            $status = 200;
            $course->load('sessions', 'sessions.course');
        }
        return array('status' => $status, 'data' => $course);
    }

    public function getSessions(){
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $courses = Course::with(['sessions'=>function($query){
            $query->with(['students'=>function($subQuery){
                $userAuth = JWTAuth::parseToken()->authenticate();
                $subQuery->where('session_user.user_id','=',$userAuth->id);
            }])->get();
        }])->get();
        $status = 404;
        if (sizeof($courses) > 0) {
            $status = 200;
            $courses->load('department', 'sessions');
        }
        return array('status' => $status, 'data' => $courses);
    }
    public function getSessionAssignments($session_id){

    }

    // IS THIS NEEDED?
    public function postCourseSessionUpload()
    {
        return array('postCourseSessionUpload:session_id');
    }

    public function getAssignments()
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $session = Session::with(['assignments' => function ($query) {
            $userAuth = JWTAuth::parseToken()->authenticate();
            $query->where('student_id', '=', $userAuth->id);
        }])->get();
        $status = 404;
        if (sizeof($session) > 0) {
            $status = 200;
            $session->load('course','course.department', 'professor');
        }
        return array('status' => $status, 'data' => $session);
    }

    public function displayAssignment($assignment_id){}
    public function putAssignment($assignment_id){}
}