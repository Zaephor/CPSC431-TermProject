<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/22/15
 * Time: 1:00 AM
 */

namespace App\Http\Controllers;


class AdminController extends Controller {
    public function getCourses(){
        return array("Courses");
    }
    public function getCourseInfo(){
        return array("Courseinfo");
    }
    public function postCourseModify(){
        return array("postCourseModify");
    }
    public function putCourseAdd(){
        return array("putCourseAdd");
    }
    public function deleteCourseDelete(){
        return array("deleteCourseDelete");
    }
    public function putSessionAdd(){
        return array("putSessionAdd");
    }
    public function deleteSessionDelete(){
        return array("deleteSessionDelete");
    }
}