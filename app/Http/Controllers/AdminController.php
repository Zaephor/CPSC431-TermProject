<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/22/15
 * Time: 1:00 AM
 */

namespace App\Http\Controllers;


use App\Course;
use App\Session;
use JWTAuth;

class AdminController extends Controller
{
    public function getCourses()
    {
        $courses = Course::all();
        $status = 404;
        if (sizeof($courses) > 0) {
            $status = 200;
            $courses->load('department');
        }
        return array('status' => $status, 'data' => $courses);
    }

    public function getCourseInfo($course_id)
    {
        $course = Course::find($course_id);
        $status = 404;
        if (sizeof($course) == 1) {
            $status = 200;
            $course->load('department', 'sessions', 'sessions.professor');
        }
        return array('status' => $status, 'data' => $course);
    }

    public function putCourseAdd()
    {
        $course = Course::create([
            'department_id' => Input::get('department_id'),
            'title' => Input::get('title'),
            'description' => Input::get('description'),
            'code' => Input::get('code'),
            'unitval' => Input::get('unitval')
        ]);
        $result = $course->push();
        $status = 304;
        if ($result == true) {
            $status = 200;
            $course->load('department', 'sessions', 'sessions.professor');
        }
        return array('status' => $status, 'data' => $course);
    }

    public function deleteCourseDelete($course_id)
    {
        $course = Course::find($course_id);
        $status = 404;
        if (sizeof($course) == 1) {
            $status = 200;
            $course->delete();
        }
        return array('status' => $status);
    }

    public function getSessions(){
        $courses = Course::all();
        $status = 404;
        if (sizeof($courses) > 0) {
            $status = 200;
            $courses->load('department','sessions');
        }
        return array('status' => $status, 'data' => $courses);
    }
    public function putSessionAdd()
    {
        $session = Session::create([
            'course_id' => Input::get('course_id'),
            'professor_id' => Input::get('professor_id'),
            'begins_on' => Input::get('begins_on'),
            'ends_on' => Input::get('ends_on')
        ]);
        $result = $session->push();
        $status = 304;
        if ($result == true) {
            $status = 200;
            $session->load('course', 'professor', 'course.department');
        }
        return array('status' => $status, 'data' => $session);
    }

    public function getSessionInfo($session_id)
    {
        $session = Session::find($session_id);
        $status = 404;
        if (sizeof($session) == 1) {
            $status = 200;
            $session->load('course', 'course.department', 'professor', 'assignments', 'students');
        }
        return array('status' => $status, 'data' => $session);
    }

    public function deleteSessionDelete($session_id)
    {
        $session = Session::find($session_id);
        $status = 404;
        if (sizeof($session) == 1) {
            $status = 200;
            $session->delete();
        }
        return array('status' => $status);
    }

    public function postCourseModify($course_id)
    {
        return array("postCourseModify");
    }

    public function postSessionModify($session_id)
    {
        return array("postSessionModify");
    }

}