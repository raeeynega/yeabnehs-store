<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // OWASP A05:2021 — Security Misconfiguration
        // ISO 27001 A.14.1.2 — Security in application services

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Clickjacking protection
        $response->headers->set('X-Frame-Options', 'DENY');

        // XSS protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy — ISO 27001 A.13.2.1
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy — restrict browser features
        $response->headers->set('Permissions-Policy', 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=(), interest-cohort=()');

        // HSTS — ISO 27001 A.14.1.3
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // CSP — OWASP A03:2021 Injection prevention
        $response->headers->set('Content-Security-Policy', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",
        ]. '; ');

        // Cache control for sensitive pages — ISO 27001 A.12.4.4
        if (in_array($request->route()?->getName(), [
            'login', 'register', 'account', 'checkout',
            'payment.select', 'payment.instructions', 'payment.pending',
            'admin.dashboard', 'admin.activity-log', 'admin.security-events',
        ])) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Remove server identification headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Unique request ID for traceability — ISO 27001 A.12.4.1
        $response->headers->set('X-Request-ID', $request->header('X-Request-ID', bin2hex(random_bytes(16))));

        return $response;
    }
}
