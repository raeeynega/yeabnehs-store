<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimiter
{
    private const LIMITS = [
        'global'  => ['maxAttempts' => 60, 'decayMinutes' => 1],
        'auth'    => ['maxAttempts' => 5,  'decayMinutes' => 1],
        'checkout' => ['maxAttempts' => 10, 'decayMinutes' => 1],
        'api'     => ['maxAttempts' => 120, 'decayMinutes' => 1],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $limitKey = $this->resolveRateLimitKey($request);

        if (RateLimiter::tooManyAttempts($limitKey, self::LIMITS[$limitKey]['maxAttempts'])) {
            $retryAfter = RateLimiter::availableIn($limitKey);

            ActivityLogger::securityEvent(
                'rate_limit_exceeded',
                'medium',
                "Rate limit exceeded for {$limitKey}: {$request->ip()}",
                null,
                $request->ip(),
                [
                    'url'    => $request->url(),
                    'method' => $request->method(),
                    'limit'  => $limitKey,
                    'retry'  => $retryAfter,
                ]
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'error'   => 'Too many requests',
                    'message' => "Rate limit exceeded. Try again in {$retryAfter} seconds.",
                    'retry_after' => $retryAfter,
                ], 429)->withHeaders([
                    'Retry-After' => $retryAfter,
                    'X-RateLimit-Limit' => self::LIMITS[$limitKey]['maxAttempts'],
                    'X-RateLimit-Remaining' => 0,
                ]);
            }

            return redirect()->back()->withErrors([
                'rate_limit' => "Too many requests. Please wait {$retryAfter} seconds.",
            ])->withHeaders([
                'Retry-After' => $retryAfter,
            ]);
        }

        RateLimiter::hit($limitKey, self::LIMITS[$limitKey]['decayMinutes'] * 60);

        /** @var Response $response */
        $response = $next($request);

        // Add rate limit headers — OWASP A05:2021
        $response->headers->set('X-RateLimit-Limit', self::LIMITS[$limitKey]['maxAttempts']);
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($limitKey, self::LIMITS[$limitKey]['maxAttempts']));

        return $response;
    }

    private function resolveRateLimitKey(Request $request): string
    {
        $ip = $request->ip();

        if ($request->is('login') || $request->is('register')) {
            return "auth:{$ip}";
        }

        if ($request->is('checkout') || $request->is('order*') || $request->is('pay*')) {
            return "checkout:{$ip}";
        }

        if ($request->is('api/*')) {
            return "api:{$ip}";
        }

        return "global:{$ip}";
    }
}
