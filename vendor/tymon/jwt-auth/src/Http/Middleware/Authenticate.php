<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Http\Middleware;

use Closure;

class Authenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

          
        try {
            $this->authenticate($request);

          }catch(\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e) {
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
