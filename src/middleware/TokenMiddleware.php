<?php

namespace Duxingyu\Token\Middleware;

use Closure;
use Duxingyu\Token\Token;
use Illuminate\Http\Request;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->validatorToken($request)) {
            return response(['msg' => 'Token失效请重新登录', 'code' => 401]);
        }
        return $next($request);
    }

    /**
     * @param Request $request
     * @return false
     */
    private function validatorToken(Request $request)
    {
        $token = $request->header('accessToken');
        if (!$token) {
            return false;
        }
        if (!Token::init()->validatorToken($token)) {
            return false;
        }
        return true;
    }
}
