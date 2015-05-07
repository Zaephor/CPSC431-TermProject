<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 5:50 PM
 */

namespace App\Http\Controllers;

use App\User;
use App\Assignment;
use App\Session;
use JWTAuth;

class StudentController extends Controller {
    public function getGrades(){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $assignments = Assignment::where('student_id','=',$student_id)->get();
        $status = 404;
        if(sizeof($assignments) > 0){
            $status = 200;
            $assignments->load('session','session.course','session.course.department','session.professor','session.assignments');
        }
        return array('status'=>$status,'data'=>$assignments);
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
        if (! $userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $user = User::find($userAuth->id); // Get student ID from user token or session
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