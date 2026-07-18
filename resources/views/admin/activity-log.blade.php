@extends('admin.layout')
@section('title', 'Activity Log')
@section('header', 'Activity Log')

@section('content')
<!-- Filters -->
<div class="bg-white border border-gray-100 rounded-xl p-6 mb-6">
    <form method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
            class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
        <select name="user_id" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Users</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
            @endforeach
        </select>
        <select name="action" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Actions</option>
            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
            <option value="failed_login" {{ request('action') == 'failed_login' ? 'selected' : '' }}>Failed Login</option>
            <option value="request" {{ request('action') == 'request' ? 'selected' : '' }}>Request</option>
            <option value="purchase" {{ request('action') == 'purchase' ? 'selected' : '' }}>Purchase</option>
        </select>
        <select name="risk_level" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Risk Levels</option>
            <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>High</option>
            <option value="critical" {{ request('risk_level') == 'critical' ? 'selected' : '' }}>Critical</option>
        </select>
        <button type="submit" class="bg-[#0a0a0a] text-white rounded-lg px-4 py-2.5 text-[12px] font-bold uppercase tracking-wider hover:bg-gray-700 transition-colors">Filter</button>
    </form>
</div>

<!-- Log Table -->
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Time</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">User</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Action</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Description</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">IP</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Method</th>
                    <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Risk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 text-[12px] text-gray-500 whitespace-nowrap">{{ $log->created_at->format('M d H:i:s') }}</td>
                        <td class="px-5 py-3.5">
                            @if($log->user)
                                <a href="{{ route('admin.user-detail', $log->user) }}" class="text-[12px] font-semibold hover:underline">{{ $log->user->name }}</a>
                            @else
                                <span class="text-[12px] text-gray-400">System</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full
                                @if($log->action == 'login') bg-green-100 text-green-700
                                @elseif($log->action == 'logout') bg-gray-100 text-gray-600
                                @elseif($log->action == 'failed_login') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-[12px] text-gray-600 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-[12px] font-mono text-gray-500">{{ $log->ip_address }}</td>
                        <td class="px-5 py-3.5 text-[11px] font-mono text-gray-400">{{ $log->method }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $log->riskColor() }}">{{ $log->risk_level }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-[13px] text-gray-400">No activity logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
