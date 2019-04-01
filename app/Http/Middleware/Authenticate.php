<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
    use Tymon\JWTAuth\Facades\JWTAuth;
class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        try {

           $user = JWTAuth::parseToken()->authenticate($request); 

        }catch(Exception $e) {
            return response()->json([
                'error' => [
                   'title'=> 'Error de sesión',
                    'code' => 401,
                    'message' => "La sesión ha expirado o es invalida, por favor inicie sesión nuevamente",
                ]
                ], 401);
          } 
        return $next($request);
    }
}
