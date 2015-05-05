<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 4/21/15
 * Time: 11:34 PM
 */

namespace App\Http\Controllers;
use Request;

class TestController extends Controller {
    public function debug(){
        return array(
            'url'=>Request::url()
            );
    }
}