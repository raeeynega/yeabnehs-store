@extends('admin.layout')
@section('title', 'Blocked IPs')
@section('header', 'Blocked IPs')

@section('content')
<!-- Add IP Form -->
<div class="bg-white border border-gray-100 rounded-xl p-6 mb-6">
    <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Block New IP</h3>
    <form method="POST" action="{{ route('admin.block-ip') }}" class="flex gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-1.5">IP Address</label>
            <input type="text" name="ip_address" required placeholder="192.168.1.1"
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] font-mono focus:outline-none focus:border-brand transition-colors">
        </div>
        <div class="flex-1">
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Reason</label>
            <input type="text" name="reason" required placeholder="Suspicious activity..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
        </div>
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Expires (optional)</label>
            <input type="datetime-local" name="expires_at"
                class="border border-gray-200 rounded-lg px-4 py-2.5 text-[13px] focus:outline-none focus:border-brand transition-colors">
        </div>
        <button type="submit" class="bg-red-600 text-white rounded-lg px-6 py-2.5 text-[12px] font-bold uppercase tracking-wider hover:bg-red-700 transition-colors">Block</button>
    </form>
</div>

<!-- Blocked IPs Table -->
<div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">IP Address</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Reason</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Blocked By</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Expires</th>
                <th class="text-left px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ips as $ip)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-[13px] font-mono font-semibold">{{ $ip->ip_address }}</td>
                    <td class="px-5 py-4 text-[13px] text-gray-600">{{ $ip->reason }}</td>
                    <td class="px-5 py-4 text-[12px] text-gray-500">{{ $ip->blocker?->name ?? 'System' }}</td>
                    <td class="px-5 py-4 text-[12px] text-gray-500">
                        @if($ip->expires_at)
                            @if($ip->isExpired())
                                <span class="text-gray-400">Expired</span>
                            @else
                                {{ $ip->expires_at->diffForHumans() }}
                            @endif
                        @else
                            <span class="text-red-600 font-semibold">Permanent</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admin.unblock-ip', $ip) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] font-semibold text-green-600 hover:text-green-800">Unblock</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-[13px] text-gray-400">No blocked IPs</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
