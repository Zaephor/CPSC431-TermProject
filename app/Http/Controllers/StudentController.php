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
use PDF;
use Faker;
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
        //TODO Get copy of assignments from any other student in that same session, and add it to this user...
        $status = 200;
        return array('status' => $status, 'data' => "There's no major error checking here... So Hello Daniel.");
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
            $user->load('sessions', 'sessions.course', 'sessions.course.department');
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

    public function getSessions()
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $courses = Course::with(['sessions' => function ($query) {
            $query->with(['students' => function ($subQuery) {
                $userAuth = JWTAuth::parseToken()->authenticate();
                $subQuery->where('session_user.user_id', '=', $userAuth->id);
            },'professor'])->get();
        },'department'])->get();
        $status = 404;
        $rearrange = array();
        if (sizeof($courses) > 0) {
            $status = 200;
//            $courses->load('department');
            /*
                        $rearrange = $courses;
                        foreach($courses as $i=>$course){
                            if(sizeof($course->sessions) == 0) {
            //                    unset($rearrange[$i]);
                            } else {
                                foreach ($course->sessions as $j => $session) {
                                    if (sizeof($session->students) == 0) {
            //                            unset($rearrange[$i]->sessions[$j]);
                                    } else {
            //                            unset($rearrange[$i]->sessions[$j]->students);
                                    }
                                }
                            }
                        }
            */
        }
        return array('status' => $status, 'data' => $courses);
    }

    /* Assignments are handled in conjunction with another function elsewhere
    public function getSessionAssignments($session_id){
    }
    */

    public function getSpecificSession($session_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $session = Session::find($session_id);
        $status = 404;
        if (sizeof($session) > 0) {
            $status = 200;
            $session->load('course', 'course.department', 'professor');
        }
        return array('status' => $status, 'data' => $session);
    }

    // IS THIS NEEDED?
    public function postCourseSessionUpload()
    {
        return array('postCourseSessionUpload:session_id');
    }

    public function getSessionSyllabus($session_id){
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $faker = Faker\Factory::create('en_US');
        $session = Session::find($session_id);
        $status = 404;
        if (sizeof($session) > 0) {
            $status = 200;
            $session->load('course', 'course.department', 'professor');
        }
//        return array('status' => $status, 'data' => $session);
        $pdf = PDF::loadView('syllabus', ['session'=>$session,'faker'=>$faker->paragraphs(5)]);
        return view('syllabus',['session'=>$session,'faker'=>$faker->paragraphs(5)]);
//        return $pdf->download('syllabus.pdf');
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
            $session->load('course', 'course.department', 'professor');
        }
        return array('status' => $status, 'data' => $session);
    }

    public function displayAssignment($assignment_id)
    {
    }

    public function putAssignment($assignment_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        //TODO
    }
}