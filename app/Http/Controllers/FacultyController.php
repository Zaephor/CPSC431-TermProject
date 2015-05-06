<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/22/15
 * Time: 12:59 AM
 */

namespace App\Http\Controllers;


class FacultyController extends Controller {
    public function getCourse(){
        return array("Courses");
    }
    public function getGrades(){
        return array("Grades");
    }
    public function getSessions(){
        return array("Sessions");
    }
    public function getSessionAssignments(){
        return array("getSessionAssignments:session_id");
    }
    public function postSessionUpload(){
        return array("postSessionUpload:session_id");
    }

}