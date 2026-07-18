@extends('admin.layout')
@section('title', 'User Management')
@section('header', 'User Management')

@section('content')
<!-- Search -->
<div class="bg-white border border-gray-100 rounded-xl p-6 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
            class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
        <select name="role" class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
            <option value="">All Roles</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
        </select>
        <button type="submit" class="bg-[#0a0a0a] text-white rounded-lg px-6 py-2.5 text-[12px] font-bold uppercase tracking-wider hover:bg-gray-700 transition-colors">Search</button>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">User</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Role</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Status</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Provider</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Last Login</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Activity</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-[#0a0a0a] text-white flex items-center justify-center text-[10px] font-bold">{{ $user->getInitials() }}</div>
                            @endif
                            <div>
                                <a href="{{ route('admin.user-detail', $user) }}" class="text-[13px] font-semibold hover:underline">{{ $user->name }}</a>
                                <p class="text-[11px] text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full
                            @if($user->role == 'super_admin') bg-purple-100 text-purple-700
                            @elseif($user->role == 'admin') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-green-600"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Disabled</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-[12px] text-gray-500">{{ $user->provider ? ucfirst($user->provider) : 'Email' }}</td>
                    <td class="px-5 py-4 text-[12px] text-gray-500">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                    <td class="px-5 py-4 text-[12px]">
                        <span class="text-gray-500">{{ $user->activity_logs_count }} logs</span>
                        <span class="mx-1 text-gray-300">/</span>
                        <span class="{{ $user->security_events_count > 0 ? 'text-red-500 font-semibold' : 'text-gray-500' }}">{{ $user->security_events_count }} events</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.user-detail', $user) }}" class="text-[11px] font-semibold text-gray-900 hover:underline">View</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.toggle-user', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[11px] font-semibold {{ $user->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $user->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-[13px] text-gray-400">No users found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
