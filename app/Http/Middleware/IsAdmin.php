<?php
/**
 * Created by PhpStorm.
 * User: Laura
 * Date: 14.07.2019
 * Time: 17:40
 */

namespace App\Http\Middleware;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;


class IsAdmin
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {

        $admin = $this->auth;

        if(!$admin) {
            return response()->json(['success'=> false,'error' => 'Invalid Admin User'])->setStatusCode(400);
        }

        return $next($request);
    }
}