<?php
/**
 * Created by PhpStorm.
 * User: zaephor
 * Date: 5/7/15
 * Time: 11:44 AM
 */

namespace App\Http\Middleware;

use Closure;

use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Response;

class CORS implements Middleware {
    public function handle($request, Closure $next) {
        return $next($request)->header('Access-Control-Allow-Origin' , '*')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
    }
}