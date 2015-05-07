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

class StudentController extends Controller {
    public function getGrades(){
        if (! $userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $session = Session::with(['assignments'=>function($query){
            $userAuth = JWTAuth::parseToken()->authenticate();
            $query->where('student_id', '=', $userAuth->id);
        }])->get();
        $status = 404;
        if(sizeof($session) > 0){
            $status = 200;
            $session->load('course','professor');
        }
        return array('status'=>$status,'data'=>$session);
    }
    public function getCourses(){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $courses = User::find($user->id)->courses(); // I believe this will return the users courses, could be wrong though
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