@extends('admin.layout')
@section('title', __('Nginx Status'))
@section('header', __('Nginx Status'))

@section('content')
<div class="space-y-8">

    <!-- Process Status -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">Nginx</span>
            <p class="text-3xl font-extrabold mt-2 {{ $infra['nginx_running'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $infra['nginx_running'] ? 'UP' : 'DOWN' }}
            </p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">PHP-FPM</span>
            <p class="text-3xl font-extrabold mt-2 {{ $infra['php_fpm_running'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $infra['php_fpm_running'] ? 'UP' : 'DOWN' }}
            </p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">Config Test</span>
            <p class="text-lg font-extrabold mt-2 {{ str_contains($infra['config_test'], 'successful') ? 'text-green-600' : 'text-red-600' }}">
                {{ str_contains($infra['config_test'], 'successful') ? 'PASS' : 'FAIL' }}
            </p>
        </div>
        <div class="stat-card bg-white border border-gray-100 rounded-xl p-6">
            <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">Port</span>
            <p class="text-3xl font-extrabold mt-2">{{ $infra['port'] }}</p>
        </div>
    </div>

    @if(!str_contains($infra['config_test'], 'successful'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <p class="text-[12px] font-bold text-red-700 mb-2">Config Error</p>
        <pre class="text-[12px] text-red-600 whitespace-pre-wrap">{{ $infra['config_test'] }}</pre>
    </div>
    @endif

    <!-- Active Config -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Active Nginx Config</h3>
        <pre class="text-[11px] font-mono text-gray-600 bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-[400px] overflow-y-auto whitespace-pre">{{ $infra['config_content'] }}</pre>
    </div>

    <!-- Processes -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Processes</h3>
        <pre class="text-[11px] font-mono text-gray-600 bg-gray-50 p-4 rounded-lg overflow-x-auto max-h-[300px] overflow-y-auto whitespace-pre">{{ $infra['processes'] }}</pre>
    </div>

    <!-- Ports -->
    <div class="bg-white border border-gray-100 rounded-xl p-6">
        <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">Listening Ports</h3>
        <pre class="text-[11px] font-mono text-gray-600 bg-gray-50 p-4 rounded-lg overflow-x-auto whitespace-pre">{{ $infra['ports'] }}</pre>
    </div>

    <!-- Logs -->
    <div class="space-y-6">
        @foreach($logs as $type => $content)
        <div class="bg-white border border-gray-100 rounded-xl p-6">
            <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">{{ ucfirst($type) }} Log</h3>
            <pre class="text-[10px] font-mono text-gray-500 bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto max-h-[300px] overflow-y-auto whitespace-pre">{{ $content }}</pre>
        </div>
        @endforeach
    </div>

</div>
@endsection
