<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/22/15
 * Time: 12:59 AM
 */

namespace App\Http\Controllers;

use App\Course;
use App\Assignment;
use App\Session;
use JWTAuth;

class FacultyController extends Controller {
    public function getCourseInfo($course_id){
        if (! $userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $course = Course::find($course_id);
        $status = 404;
        if (sizeof($course) == 1) {
            $status = 200;
            $course->load('department', 'sessions', 'sessions.professor');
        }
        return array('status' => $status, 'data' => $course);
    }
    public function getGrades($student_id){
        if (! $userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        // Alternate ideas: $assignments = Assignment::has('students.id','=',$student_id)->get();
        $assignments = Assignment::where('student_id','=',$student_id)->get();
        $status = 404;
        if(sizeof($assignments) > 0){
            $status = 200;
            $assignments->load('session','session.course','session.course.department','session.professor');
        }
        $responseData = array();
        foreach($assignments as $entry){
            if($entry->session->professor_id == $userAuth->id){
                $responseData[] = $entry;
            }
        }
        return array('status'=>$status,'data'=>$responseData);
    }
    public function getSessions(){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $course = Course::with(['sessions',function($query){
            $userAuth = JWTAuth::parseToken()->authenticate();
            $query->where('professor_id', '=', $userAuth->id);
        }])->get();
//        $session = Session::where('professor_id','=',$user->id)->get();
        $status = 404;
        if (sizeof($course) > 0) {
            $status = 200;
//            $session->load('course', 'course.department', 'professor', 'assignments', 'students');
            $course->load('department','sessions');
        }
        return array('status' => $status, 'data' => $course);
    }
    public function getSessionAssignments(){
        return array("getSessionAssignments:session_id");
    }
    public function postSessionUpload(){
        return array("postSessionUpload:session_id");
    }

}