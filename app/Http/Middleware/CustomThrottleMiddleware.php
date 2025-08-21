<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CustomThrottleMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if ($this->limiter->tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->limiter->hit($key, 60); // 60 seconds

        $response = $next($request);

        return $response;
    }
}
