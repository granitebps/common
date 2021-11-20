<?php

namespace Microservices;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class InfluencerScope
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->userService->isInfluencer()) {
            return $next($request);
        }
        throw new AuthenticationException();
    }
}
