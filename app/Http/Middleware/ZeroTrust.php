<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ZeroTrust
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // 1. Check IP blocklist
        if (ActivityLogger::isIpBlocked($ip)) {
            ActivityLogger::securityEvent(
                'blocked_request',
                'high',
                "Request from blocked IP: {$ip}",
                null,
                $ip,
                ['url' => $request->url(), 'method' => $request->method()]
            );

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied'], 403);
            }
            abort(403, 'Access denied. Your IP has been blocked.');
        }

        // 2. Check if current user is active
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            ActivityLogger::securityEvent(
                'blocked_request',
                'high',
                "Disabled account attempted access: " . auth()->user()->email,
                auth()->id(),
                $ip
            );

            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        // 3. Check if current user is locked
        if (auth()->check() && auth()->user()->isLocked()) {
            $lockedUntil = auth()->user()->locked_until->format('H:i');
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', "Account locked. Try again after {$lockedUntil}.");
        }

        // 4. Log the request (skip static assets & health checks)
        if (!$this->isSkippable($request)) {
            ActivityLogger::log(
                'request',
                $request->method() . ' ' . $request->path(),
                null, null, 'low',
                ['status_code' => null],
                $request
            );
        }

        $response = $next($request);

        // 5. Log failed auth attempts
        if ($request->is('login') && $request->isMethod('POST') && $response->getStatusCode() === 302) {
            if (session()->has('errors')) {
                ActivityLogger::log(
                    'failed_login',
                    'Failed login attempt for: ' . $request->email,
                    null, null, 'medium',
                    ['email' => $request->email],
                    $request
                );
                ActivityLogger::recordFailedLogin($request->email, $request);
            }
        }

        return $response;
    }

    protected function isSkippable(Request $request): bool
    {
        $skip = [
            'livewire', 'favicon.ico', 'sanctum/csrf-cookie',
        ];
        foreach ($skip as $s) {
            if ($request->is($s)) return true;
        }
        return false;
    }
}
