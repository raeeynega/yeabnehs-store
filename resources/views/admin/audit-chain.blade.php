@extends('admin.layout')
@section('title', __('Audit Chain'))
@section('header', __('Audit Chain'))

@section('content')
<div class="space-y-8">

    <!-- Chain Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Total Entries') }}</span>
            <p class="text-3xl font-extrabold mt-2">{{ number_format($stats['total_entries']) }}</p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Latest Index') }}</span>
            <p class="text-3xl font-extrabold mt-2">{{ number_format($stats['latest_index']) }}</p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6 col-span-2">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Latest Hash') }}</span>
            <p class="text-[11px] font-mono mt-2 break-all text-gray-600">{{ $stats['latest_hash'] }}</p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">{{ __('Chain Integrity') }}</span>
            <p class="text-3xl font-extrabold mt-2 {{ $stats['chain_integrity'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $stats['chain_integrity'] ? 'VALID' : 'BROKEN' }}
            </p>
        </div>
    </div>

    @if($verifyResult)
    <div class="rounded-xl p-6 {{ $verifyResult['valid'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
        <p class="text-[13px] font-bold {{ $verifyResult['valid'] ? 'text-green-700' : 'text-red-700' }}">
            {{ $verifyResult['valid'] ? 'Chain integrity verified — all ' . number_format($stats['total_entries']) . ' entries valid' : 'CHAIN BROKEN at index ' . $verifyResult['brokenAt'] }}
        </p>
        @if(!empty($verifyResult['errors']))
            @foreach($verifyResult['errors'] as $error)
                <p class="text-[12px] text-red-600 mt-1">{{ $error }}</p>
            @endforeach
        @endif
    </div>
    @endif

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.audit-chain', ['verify' => 1]) }}" class="bg-gray-900 text-white px-6 py-2.5 text-[11px] font-bold uppercase tracking-[0.15em] rounded-lg hover:bg-gray-800 transition">
            Verify Chain Integrity
        </a>
        <a href="{{ route('admin.audit-chain') }}" class="text-[11px] font-semibold text-gray-500 hover:underline">Refresh</a>
    </div>

    <!-- Recent Entries -->
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400">Recent Audit Entries</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">Index</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">Timestamp</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">User</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">IP</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">Risk</th>
                        <th class="text-left px-6 py-3 font-bold text-gray-400 uppercase tracking-wider">Chain Hash</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEntries as $entry)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-mono font-semibold">{{ $entry->chain_index }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $entry->timestamp->format('M d H:i:s') }}</td>
                        <td class="px-6 py-3 font-medium">{{ $entry->action }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $entry->user?->name ?? '—' }}</td>
                        <td class="px-6 py-3 font-mono text-gray-500">{{ $entry->ip_address }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full {{ $entry->riskColor() }}">{{ $entry->risk_level }}</span>
                        </td>
                        <td class="px-6 py-3 font-mono text-[10px] text-gray-400">{{ substr($entry->chain_hash, 0, 16) }}...</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No audit entries yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
