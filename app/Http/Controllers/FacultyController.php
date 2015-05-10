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

class FacultyController extends Controller
{
    public function getGrades($student_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        // Alternate ideas: $assignments = Assignment::has('students.id','=',$student_id)->get();
        $assignments = Assignment::where('student_id', '=', $student_id)->get();
        $status = 404;
        if (sizeof($assignments) > 0) {
            $status = 200;
            $assignments->load('session', 'session.course', 'session.course.department', 'session.professor');
        }
        $responseData = array();
        foreach ($assignments as $entry) {
            if ($entry->session->professor_id == $userAuth->id) {
                $responseData[] = $entry;
            }
        }
        return array('status' => $status, 'data' => $responseData);
    }

    public function getGradesSession($student_id,$session_id){
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        // Alternate ideas: $assignments = Assignment::has('students.id','=',$student_id)->get();
        $assignments = Assignment::where('student_id', '=', $student_id)->get();
        $status = 404;
        if (sizeof($assignments) > 0) {
            $status = 200;
            $assignments->load('session', 'session.course', 'session.course.department', 'session.professor');
        }
        $responseData = array();
        foreach ($assignments as $entry) {
            if ($entry->session->professor_id == $userAuth->id && $entry->session->id == $session_id) {
                $responseData[] = $entry;
            }
        }
        return array('status' => $status, 'data' => $responseData);
    }
    public function getCourses()
    {
    }

    public function getAllCourses()
    {
    }

    public function getCourseInfo($course_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
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

    public function getSessions()
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $course = Course::with(['sessions' => function ($query) use ($userAuth) {
            $query->where('professor_id', '=', $userAuth->id)->with('professor');
        },'department'])->get();
//        $session = Session::where('professor_id','=',$user->id)->get();
        $status = 404;
        if (sizeof($course) > 0) {
            $status = 200;
//            $session->load('course', 'course.department', 'professor', 'assignments', 'students');
//            $course->load('department','professor');
        }
        return array('status' => $status, 'data' => $course);
    }

    public function getSessionAssignments()
    {
    }

    public function getSessionStudents($session_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $session = Session::find($session_id);
        $status = 404;
        if (sizeof($session) == 1) {
            $status = 200;
            $session->load('students');
        }
        return array('status' => $status, 'data' => $session);
    }

    public function getAssignment($assignment_id)
    {
    }

    public function postCreateAssignment()
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $users = User::where('session_user.session_id','=',Input::get("session_id"));
        $assignments = array();
        foreach($users as $entry) {
            $assignments[] = Assignment::create([
                'session_id' => Input::get("session_id"),
                'student_id' =>$entry->id,
                'assignment_code' => Input::get,
                'score' => null
            ]);
        }
        $status = 401;
        if (sizeof($users) == sizeof($assignments)) {
            $status = 200;
        }
        return array('status' => $status, 'data' => $assignments);
    }

    public function putModifyAssignment($assignment_id)
    {
        if (!$userAuth = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $assignments = Assignment::find($assignment_id);
        $assignments->score = Input::get('score');
        $result = $assignments->push();
        $status = 401;
        if ($result == true) {
            $status = 200;
        }
        return array('status' => $status, 'data' => $assignments);
    }

    public function deleteDeleteAssignment($assignment_id)
    {
    }

    // ????
    public function postSessionUpload()
    {
        return array("postSessionUpload:session_id");
    }

}