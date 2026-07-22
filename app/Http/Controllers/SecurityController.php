<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\BlockedIp;
use App\Models\SecurityEvent;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\ImmutableAuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    public function dashboard()
    {
        $now = now();

        // Stats
        $stats = [
            'total_users' => User::count(),
            'active_users_24h' => ActivityLog::where('created_at', '>=', $now->subDay())->distinct('user_id')->count('user_id'),
            'total_requests_24h' => ActivityLog::where('created_at', '>=', $now->subDay())->count(),
            'security_events_24h' => SecurityEvent::where('created_at', '>=', $now->subDay())->count(),
            'unresolved_events' => SecurityEvent::where('resolved', false)->count(),
            'blocked_ips' => BlockedIp::count(),
            'failed_logins_24h' => SecurityEvent::where('event_type', 'failed_login')->where('created_at', '>=', $now->subDay())->count(),
            'critical_events' => SecurityEvent::where('severity', 'critical')->where('resolved', false)->count(),
        ];

        // Risk breakdown
        $riskBreakdown = ActivityLog::where('created_at', '>=', $now->subDays(7))
            ->select('risk_level', DB::raw('count(*) as total'))
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');

        // Requests per day (last 7 days)
        $requestsPerDay = ActivityLog::where('created_at', '>=', $now->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Top IPs
        $topIps = ActivityLog::where('created_at', '>=', $now->subDays(7))
            ->select('ip_address', DB::raw('count(*) as total'), DB::raw('COUNT(DISTINCT user_id) as unique_users'))
            ->groupBy('ip_address')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Security events breakdown
        $eventsByType = SecurityEvent::where('created_at', '>=', $now->subDays(7))
            ->select('event_type', DB::raw('count(*) as total'))
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        // Critical events
        $criticalEvents = SecurityEvent::where('resolved', false)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Nginx / Infrastructure status
        $nginxRunning = str_contains(shell_exec('ps aux | grep "[n]ginx" 2>/dev/null') ?? '', 'nginx');
        $phpFpmRunning = str_contains(shell_exec('ps aux | grep "[p]hp-fpm" 2>/dev/null') ?? '', 'php-fpm');
        $nginxConfigTest = trim(shell_exec('nginx -t 2>&1') ?? 'unknown');
        $appPort = env('PORT', '80');

        $infra = [
            'nginx_running'     => $nginxRunning,
            'php_fpm_running'   => $phpFpmRunning,
            'nginx_config_ok'   => str_contains($nginxConfigTest, 'successful'),
            'nginx_config_error' => str_contains($nginxConfigTest, 'failed') ? $nginxConfigTest : null,
            'app_port'          => $appPort,
            'server_name'       => gethostname() ?? 'unknown',
        ];

        return view('admin.dashboard', compact(
            'stats', 'riskBreakdown', 'requestsPerDay',
            'topIps', 'eventsByType', 'criticalEvents', 'infra'
        ));
    }

    public function activityLog(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('url', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%");
            });
        }

        $logs = $query->paginate(50)->withQueryString();
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('admin.activity-log', compact('logs', 'users'));
    }

    public function securityEvents(Request $request)
    {
        $query = SecurityEvent::with('user')->latest();

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }
        if ($request->filled('resolved')) {
            $query->where('resolved', $request->boolean('resolved'));
        }

        $events = $query->paginate(50)->withQueryString();

        return view('admin.security-events', compact('events'));
    }

    public function users(Request $request)
    {
        $query = User::withCount('activityLogs', 'securityEvents');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(30)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function userDetail(User $user)
    {
        $user->loadCount('activityLogs', 'securityEvents');
        $recentActivity = $user->activityLogs()->latest()->limit(50)->get();
        $securityEvents = $user->securityEvents()->latest()->limit(20)->get();

        $uniqueIps = $user->activityLogs()
            ->select('ip_address', DB::raw('count(*) as total'), DB::raw('MAX(created_at) as last_seen'))
            ->groupBy('ip_address')
            ->orderByDesc('last_seen')
            ->get();

        return view('admin.user-detail', compact('user', 'recentActivity', 'securityEvents', 'uniqueIps'));
    }

    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string|max:45',
            'reason' => 'required|string|max:500',
            'expires_at' => 'nullable|date|after:now',
        ]);

        BlockedIp::updateOrCreate(
            ['ip_address' => $request->ip_address],
            [
                'reason' => $request->reason,
                'blocked_by' => auth()->id(),
                'expires_at' => $request->expires_at,
            ]
        );

        ActivityLogger::securityEvent(
            'blocked_request',
            'high',
            "IP blocked: {$request->ip_address} - {$request->reason}",
            null,
            $request->ip_address,
            ['blocked_by' => auth()->user()->name, 'expires' => $request->expires_at]
        );

        return back()->with('success', "IP {$request->ip_address} has been blocked.");
    }

    public function unblockIp(BlockedIp $ip)
    {
        $ip->delete();
        return back()->with('success', 'IP has been unblocked.');
    }

    public function blockedIps()
    {
        $ips = BlockedIp::with('blocker')->latest()->get();
        return view('admin.blocked-ips', compact('ips'));
    }

    public function resolveEvent(SecurityEvent $event)
    {
        $event->update([
            'resolved' => true,
            'resolved_by' => auth()->user()->name,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Event resolved.');
    }

    public function toggleUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$user->name} has been {$status}.");
    }

    public function nginxStatus()
    {
        $logs = [
            'error'   => $this->readLogTail('/var/log/nginx/error.log', 100),
            'access'  => $this->readLogTail('/var/log/nginx/access.log', 100),
            'supervisor_nginx'  => $this->readLogTail('/var/log/supervisor/nginx.err.log', 50),
            'supervisor_php'    => $this->readLogTail('/var/log/supervisor/php-fpm.err.log', 50),
        ];

        $infra = [
            'nginx_running'   => str_contains(shell_exec('ps aux | grep "[n]ginx" 2>/dev/null') ?? '', 'nginx'),
            'php_fpm_running' => str_contains(shell_exec('ps aux | grep "[p]hp-fpm" 2>/dev/null') ?? '', 'php-fpm'),
            'config_test'     => trim(shell_exec('nginx -t 2>&1') ?? 'unknown'),
            'config_content'  => file_get_contents('/etc/nginx/conf.d/default.conf') ?: 'NOT FOUND',
            'processes'       => shell_exec('ps aux | grep -E "[n]ginx|[p]hp-fpm" 2>/dev/null') ?? 'none',
            'ports'           => shell_exec('ss -tlnp 2>/dev/null || netstat -tlnp 2>/dev/null') ?? 'unknown',
            'port'            => env('PORT', '80'),
            'hostname'        => gethostname() ?? 'unknown',
        ];

        return view('admin.nginx', compact('logs', 'infra'));
    }

    public function auditChain()
    {
        $stats = ImmutableAuditLogger::chainStats();
        $recentEntries = AuditLog::orderByDesc('chain_index')->limit(50)->get();

        $verifyResult = null;
        if (request()->has('verify')) {
            $verifyResult = ImmutableAuditLogger::verifyChain();
        }

        return view('admin.audit-chain', compact('stats', 'recentEntries', 'verifyResult'));
    }

    private function readLogTail(string $path, int $lines): string
    {
        if (!file_exists($path)) {
            return "[file not found: {$path}]";
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return "[could not read: {$path}]";
        }

        $allLines = explode("\n", $content);
        $tail = array_slice($allLines, -$lines);
        return implode("\n", $tail);
    }
}
