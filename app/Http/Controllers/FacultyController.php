<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/22/15
 * Time: 12:59 AM
 */

namespace App\Http\Controllers;

use App\Assignment;
use JWTAuth;

class FacultyController extends Controller {
    public function getCourse($course_id){
        $course = Course::find($course_id);
        $status = 404;
        if (sizeof($course) == 1) {
            $status = 200;
            $course->load('department', 'sessions', 'sessions.professor');
        }
        return array('status' => $status, 'data' => $course);
    }
    public function getGrades($student_id){
        // Alternate ideas: $assignments = Assignment::has('students.id','=',$student_id)->get();
        $assignments = Assignment::where('student_id','=',$student_id)->get();
        $status = 404;
        if(sizeof($assignments) > 0){
            $status = 200;
            $assignments->load('session','session.course','session.course.department','session.professor');
        }
        return array('status'=>$status,'data'=>$assignments);
    }
    public function getSessions(){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $professor_id = $user->id;
//        $professor_id = Input::get('professor_id');//TODO Check that this works, probably should come from user session/token
        $session = Session::where('professor_id','=',$professor_id);
        $status = 404;
        if (sizeof($session) == 1) {
            $status = 200;
            $session->load('course', 'course.department', 'professor', 'assignments', 'students');
        }
        return array('status' => $status, 'data' => $session);
    }
    public function getSessionAssignments(){
        return array("getSessionAssignments:session_id");
    }
    public function postSessionUpload(){
        return array("postSessionUpload:session_id");
    }

}