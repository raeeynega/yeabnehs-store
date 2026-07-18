@extends('admin.layout')
@section('title', $user->name . ' - User Detail')
@section('header', 'User Detail')

@section('content')
<!-- User Header -->
<div class="bg-white border border-gray-100 rounded-xl p-8 mb-6">
    <div class="flex items-center gap-6">
        @if($user->avatar)
            <img src="{{ $user->avatar }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-100">
        @else
            <div class="w-20 h-20 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-2xl font-bold">{{ $user->getInitials() }}</div>
        @endif
        <div class="flex-1">
            <h2 class="text-2xl font-extrabold">{{ $user->name }}</h2>
            <p class="text-[14px] text-gray-500">{{ $user->email }}</p>
            <div class="flex items-center gap-3 mt-2">
                <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full
                    @if($user->role == 'super_admin') bg-purple-100 text-purple-700
                    @elseif($user->role == 'admin') bg-blue-100 text-blue-700
                    @else bg-gray-100 text-gray-600 @endif">
                    {{ $user->role }}
                </span>
                @if($user->is_active)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-green-600"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active</span>
                @else
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Disabled</span>
                @endif
                @if($user->provider)
                    <span class="text-[11px] text-gray-400">via {{ ucfirst($user->provider) }}</span>
                @endif
            </div>
        </div>
        <div class="text-right">
            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.toggle-user', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-wider
                        {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }} transition-colors">
                        {{ $user->is_active ? 'Disable Account' : 'Enable Account' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-xl p-5 text-center">
        <p class="text-2xl font-extrabold">{{ $user->activity_logs_count }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-bold">Activity Logs</p>
    </div>
    <div class="bg-white border border-gray-100 rounded-xl p-5 text-center">
        <p class="text-2xl font-extrabold {{ $user->security_events_count > 0 ? 'text-red-600' : '' }}">{{ $user->security_events_count }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-bold">Security Events</p>
    </div>
    <div class="bg-white border border-gray-100 rounded-xl p-5 text-center">
        <p class="text-2xl font-extrabold">{{ $uniqueIps->count() }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-bold">Unique IPs</p>
    </div>
    <div class="bg-white border border-gray-100 rounded-xl p-5 text-center">
        <p class="text-2xl font-extrabold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider font-bold">Last Login</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Recent Activity</h3>
        <div class="space-y-3 max-h-[400px] overflow-y-auto">
            @forelse($recentActivity as $log)
                <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full shrink-0
                        @if($log->action == 'login') bg-green-100 text-green-700
                        @elseif($log->action == 'failed_login') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ str_replace('_', ' ', $log->action) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] text-gray-600 line-clamp-1">{{ $log->description }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }} &middot; {{ $log->ip_address }}</p>
                    </div>
                </div>
            @empty
                <p class="text-[13px] text-gray-400 text-center py-4">No activity</p>
            @endforelse
        </div>
    </div>

    <!-- IP Addresses -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">IP Addresses Used</h3>
        <div class="space-y-3">
            @forelse($uniqueIps as $ip)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-[13px] font-mono font-semibold">{{ $ip->ip_address }}</p>
                        <p class="text-[10px] text-gray-400">Last seen: {{ $ip->last_seen->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[12px] font-bold">{{ $ip->total }} requests</span>
                        <form method="POST" action="{{ route('admin.block-ip') }}">
                            @csrf
                            <input type="hidden" name="ip_address" value="{{ $ip->ip_address }}">
                            <input type="hidden" name="reason" value="Blocked from user detail: {{ $user->name }}">
                            <button type="submit" class="text-[10px] font-bold text-red-600 hover:text-red-800 uppercase tracking-wider" onclick="return confirm('Block this IP?')">Block</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-[13px] text-gray-400 text-center py-4">No IPs recorded</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Security Events -->
@if($securityEvents->count())
    <div class="bg-white border border-gray-100 rounded-xl p-6 mt-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Security Events for this User</h3>
        <div class="space-y-3">
            @foreach($securityEvents as $event)
                <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                    <span class="inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full {{ $event->severityColor() }} shrink-0">{{ $event->severity }}</span>
                    <div class="flex-1">
                        <p class="text-[12px] font-semibold">{{ str_replace('_', ' ', ucfirst($event->event_type)) }}</p>
                        <p class="text-[12px] text-gray-500 mt-0.5">{{ $event->description }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $event->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$event->resolved)
                        <form method="POST" action="{{ route('admin.resolve-event', $event) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-[10px] font-bold text-green-600 hover:underline">Resolve</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
