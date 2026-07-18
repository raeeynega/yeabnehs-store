<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\BlockedIp;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $action,
        ?string $description = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?string $riskLevel = 'low',
        ?array $properties = null,
        ?Request $request = null
    ): ActivityLog {
        $request = $request ?? request();

        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'method' => $request->method(),
            'properties' => $properties,
            'risk_level' => $riskLevel,
        ]);
    }

    public static function securityEvent(
        string $eventType,
        string $severity,
        string $description,
        ?int $userId = null,
        ?string $ip = null,
        ?array $metadata = null
    ): SecurityEvent {
        $request = request();

        return SecurityEvent::create([
            'user_id' => $userId ?? Auth::id(),
            'event_type' => $eventType,
            'severity' => $severity,
            'description' => $description,
            'ip_address' => $ip ?? $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    public static function recordLogin($user, Request $request): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0,
        ]);

        static::log('login', 'User logged in successfully', get_class($user), $user->id, 'low', [
            'provider' => $user->provider ?? 'email',
        ]);
    }

    public static function recordFailedLogin(string $email, Request $request): void
    {
        static::securityEvent(
            'failed_login',
            'medium',
            "Failed login attempt for: {$email}",
            null,
            $request->ip(),
            ['email' => $email]
        );

        // Check for brute force
        $recentFailed = ActivityLog::where('action', 'failed_login')
            ->where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();

        if ($recentFailed >= 5) {
            static::securityEvent(
                'brute_force',
                'high',
                "Brute force detected from IP: {$request->ip()} ({$recentFailed} attempts in 15min)",
                null,
                $request->ip(),
                ['attempts' => $recentFailed, 'window' => '15 minutes']
            );
        }
    }

    public static function isIpBlocked(string $ip): bool
    {
        return BlockedIp::where('ip_address', $ip)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}
