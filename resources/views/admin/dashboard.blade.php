@extends('admin.layout')
@section('title', __('Security Overview'))
@section('header', __('Security Overview'))

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Total Users') }}</span>
            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['total_users']) }}</p>
        <p class="text-[11px] text-gray-400 mt-1">{{ $stats['active_users_24h'] }} {{ __('active (24h)') }}</p>
    </div>

    <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Requests (24h)') }}</span>
            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['total_requests_24h']) }}</p>
        <p class="text-[11px] text-gray-400 mt-1">{{ __('All tracked requests') }}</p>
    </div>

    <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Failed Logins (24h)') }}</span>
            <div class="w-9 h-9 rounded-lg bg-gray-900 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['failed_logins_24h']) }}</p>
        <p class="text-[11px] text-gray-400 mt-1">{{ __('Authentication attempts') }}</p>
    </div>

    <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Unresolved Events') }}</span>
            <div class="w-9 h-9 rounded-lg {{ $stats['unresolved_events'] > 0 ? 'bg-gray-900' : 'bg-gray-100' }} flex items-center justify-center">
                <svg class="w-4 h-4 {{ $stats['unresolved_events'] > 0 ? 'text-white' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold">{{ $stats['unresolved_events'] }}</p>
        <p class="text-[11px] text-gray-400 mt-1">{{ $stats['blocked_ips'] }} {{ __('IPs blocked') }}</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
    <!-- Requests Chart -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">{{ __('Requests (7 Days)') }}</h3>
        <canvas id="requestsChart" height="200"></canvas>
    </div>

    <!-- Risk Breakdown -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">{{ __('Risk Breakdown (7 Days)') }}</h3>
        <canvas id="riskChart" height="200"></canvas>
    </div>
</div>

<!-- Top IPs + Critical Events -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <!-- Top IPs -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">{{ __('Top IPs (7 Days)') }}</h3>
        <div class="space-y-3">
            @forelse($topIps as $ip)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-[13px] font-mono font-semibold">{{ $ip->ip_address }}</p>
                        <p class="text-[11px] text-gray-400">{{ $ip->unique_users }} {{ __('users') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[14px] font-bold">{{ number_format($ip->total) }}</p>
                        <p class="text-[10px] text-gray-400">{{ __('requests') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-[13px] text-gray-400 py-4 text-center">{{ __('No data yet') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Critical Events -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Unresolved Security Events') }}</h3>
            <a href="{{ route('admin.security-events', ['resolved' => 0]) }}" class="text-[11px] font-semibold text-gray-600 hover:underline">{{ __('View All Events') }}</a>
        </div>
        <div class="space-y-3">
            @forelse($criticalEvents as $event)
                <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full {{ $event->severityColor() }} shrink-0 mt-0.5">
                        {{ $event->severity }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] font-semibold line-clamp-1">{{ $event->description }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $event->created_at->diffForHumans() }} &middot; {{ $event->ip_address }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.resolve-event', $event) }}" class="shrink-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-[10px] font-semibold text-gray-600 hover:underline whitespace-nowrap">{{ __('Resolve') }}</button>
                    </form>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[13px] text-gray-400">{{ __('All clear!') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const requestsData = @json($requestsPerDay);
    const riskData = @json($riskBreakdown);

    // Requests Chart
    new Chart(document.getElementById('requestsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(requestsData).map(d => new Date(d).toLocaleDateString('en', { weekday: 'short' })),
            datasets: [{
                label: 'Requests',
                data: Object.values(requestsData),
                backgroundColor: '#0a0a0a',
                borderRadius: 6,
                barPercentage: 0.5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f5f5f5' }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    // Risk Chart - grayscale
    const riskColors = { low: '#d1d5db', medium: '#9ca3af', high: '#4b5563', critical: '#111827' };
    new Chart(document.getElementById('riskChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(riskData).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
            datasets: [{
                data: Object.values(riskData),
                backgroundColor: Object.keys(riskData).map(k => riskColors[k] || '#e5e7eb'),
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, font: { size: 12 } } }
            }
        }
    });
});
</script>
@endpush
