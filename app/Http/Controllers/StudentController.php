<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 5:50 PM
 */

namespace App\Http\Controllers;


class StudentController extends Controller {
    public function getGrades(){
        return array('Grades');
    }
    public function getCourses(){
        return array('Courses');
    }

    public function postEnroll(){
        return array('Enroll');
    }
    public function getCourseSession(){
        return array('getCourseSession:session_id');
    }
    public function postCourseSessionUpload(){
        return array('postCourseSessionUpload:session_id');
    }
}