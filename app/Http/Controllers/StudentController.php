<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 5:50 PM
 */

namespace App\Http\Controllers;

use App\User;

class StudentController extends Controller {
    public function getGrades(){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
//        $assignments = Assignment::where('student_id','=',$student_id)->get();
        $sessions = Session::where('assignments.student_id','=',$user->id)->get();
        $status = 404;
        if(sizeof($sessions) > 0){
            $status = 200;
//            $assignments->load('session','session.course','session.course.department','session.professor');
            $sessions->load('course','course.department','assignments');
        }
        return array('status'=>$status,'data'=>$sessions);
    }
    public function getCourses(){
        $student_id = Input::get('student_id');//TODO Check that this works, probably should come from user session/token
        $courses = User::find($student_id)->courses(); // I believe this will return the users courses, could be wrong though
        $status = 404;
        if(sizeof($courses) > 0){
            $status = 200;
            $courses->load('department');
        }
        return array('status'=>$status,'data'=>$courses);
    }

    public function postEnroll($session_id){
        $user = User::find($student_id); // Get student ID from user token or session
        $user->sessions()->attach($session_id);
        $status = 200;
        return array('status'=>$status,'data'=>$courses);
    }
    public function getCourseSession(){
        return array('getCourseSession:session_id');
    }
    public function postCourseSessionUpload(){
        return array('postCourseSessionUpload:session_id');
    }
}