<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Abilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $access;
    private $administrator;
    private $permissions;
    private $route;

    public function handle(Request $request, Closure $next): Response
    {
        if(Session::has('administrator')){
            $this->administrator = Session::get('administrator');
        }
        if(Session::has('access')){
            $this->access = Session::get('access');
        }
        if(Session::has('permissions')){
            $this->permissions = Session::get('permissions');
        }
        if ($this->administrator) {
            return $next($request);
        }

        $this->route = md5(trim($request->route()->getName()));
        
        if (!isset($this->permissions[$this->route])) {
            return $next($request);
        }
        if (isset($this->permissions[$this->route]) && isset($this->access[$this->route])) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.access_denied'),
            ], 403);
        }
        return abort(403, __('messages.access_denied'));
    }
}
