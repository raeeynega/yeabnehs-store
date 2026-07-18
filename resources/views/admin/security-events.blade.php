@extends('admin.layout')
@section('title', 'Security Events')
@section('header', 'Security Events')

@section('content')
<!-- Filters -->
<div class="bg-white border border-gray-100 rounded-xl p-6 mb-6">
    <form method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <select name="severity" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Severities</option>
            <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
        </select>
        <select name="type" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Types</option>
            <option value="failed_login" {{ request('type') == 'failed_login' ? 'selected' : '' }}>Failed Login</option>
            <option value="brute_force" {{ request('type') == 'brute_force' ? 'selected' : '' }}>Brute Force</option>
            <option value="blocked_request" {{ request('type') == 'blocked_request' ? 'selected' : '' }}>Blocked Request</option>
            <option value="suspicious_activity" {{ request('type') == 'suspicious_activity' ? 'selected' : '' }}>Suspicious Activity</option>
        </select>
        <select name="resolved" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Status</option>
            <option value="0" {{ request('resolved') === '0' ? 'selected' : '' }}>Unresolved</option>
            <option value="1" {{ request('resolved') === '1' ? 'selected' : '' }}>Resolved</option>
        </select>
        <button type="submit" class="bg-[#0a0a0a] text-white rounded-lg px-4 py-2.5 text-[12px] font-bold uppercase tracking-wider hover:bg-gray-700 transition-colors">Filter</button>
    </form>
</div>

<!-- Events List -->
<div class="space-y-3">
    @forelse($events as $event)
        <div class="bg-white border border-gray-100 rounded-xl p-5 flex items-start gap-4 hover:border-gray-200 transition-colors">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                @if($event->severity == 'critical') bg-red-100
                @elseif($event->severity == 'high') bg-orange-100
                @elseif($event->severity == 'medium') bg-yellow-100
                @else bg-blue-100 @endif">
                <svg class="w-5 h-5 @if($event->severity == 'critical') text-red-600
                @elseif($event->severity == 'high') text-orange-600
                @elseif($event->severity == 'medium') text-yellow-600
                @else text-blue-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full {{ $event->severityColor() }}">{{ $event->severity }}</span>
                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full bg-gray-100 text-gray-600">{{ str_replace('_', ' ', $event->event_type) }}</span>
                    @if($event->resolved)
                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full bg-green-100 text-green-700">resolved</span>
                    @endif
                </div>
                <p class="text-[13px] font-semibold mt-1">{{ $event->description }}</p>
                <div class="flex items-center gap-4 mt-2 text-[11px] text-gray-400">
                    @if($event->user)
                        <a href="{{ route('admin.user-detail', $event->user) }}" class="text-gray-900 font-medium hover:underline">{{ $event->user->name }}</a>
                    @endif
                    <span>IP: {{ $event->ip_address }}</span>
                    <span>{{ $event->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if(!$event->resolved)
                <form method="POST" action="{{ route('admin.resolve-event', $event) }}" class="shrink-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-50 text-green-700 px-4 py-2 rounded-lg text-[11px] font-bold uppercase tracking-wider hover:bg-green-100 transition-colors">Resolve</button>
                </form>
            @else
                <div class="text-[10px] text-gray-400 shrink-0 text-right">
                    <p>By {{ $event->resolved_by }}</p>
                    <p>{{ $event->resolved_at->diffForHumans() }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border border-gray-100 rounded-xl p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-[14px] text-gray-400">No security events found</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection
