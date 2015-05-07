<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 6:08 PM
 */

namespace app\Http\Controllers;


class UserController extends Controller {
    public function postRegister(){
        return array("Register");
    }
    public function postLogin(){
        return array("Login");
    }
    public function postLogout(){
        return array("Logout");
    }
}