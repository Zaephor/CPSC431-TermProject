<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/4/15
 * Time: 6:08 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller {
    public function postRegister(){
        return array("Register");
    }
    public function postLogin(Request $request){
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            $custom_claim = array();
            if(Auth::attempt($credentials)){
                $custom_claim['user'] = Auth::user();
            }
            if (! $token = JWTAuth::attempt($credentials,$custom_claim)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
    public function postLogout(){
        return array("Logout");
    }
}