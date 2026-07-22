<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Security Dashboard')) - YeaBneh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        ::selection { background: #fff; color: #0a0a0a; }
        .sidebar-link.active { background: #fff; color: #0a0a0a; font-weight: 600; }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.08); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#0a0a0a] text-white min-h-screen fixed left-0 top-0 bottom-0 z-40 flex flex-col">
        <div class="p-6 border-b border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-1 mb-1">
                <span class="text-lg font-extrabold tracking-[-0.04em] uppercase">YeaBneh</span>
            </a>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/50">{{ __('Security Dashboard') }}</span>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                {{ __('Overview') }}
            </a>
            <a href="{{ route('admin.activity-log') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.activity-log') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ __('Activity Log') }}
            </a>
            <a href="{{ route('admin.security-events') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.security-events') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                {{ __('Security Events') }}
                @php $unresolved = \App\Models\SecurityEvent::where('resolved', false)->count(); @endphp
                @if($unresolved > 0)
                    <span class="ml-auto bg-white text-[#0a0a0a] text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $unresolved > 9 ? '9+' : $unresolved }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                {{ __('Users') }}
            </a>
            <a href="{{ route('admin.blocked-ips') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium hover:bg-white/10 transition {{ request()->routeIs('admin.blocked-ips') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                {{ __('Blocked IPs') }}
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 rounded-full bg-white text-[#0a0a0a] flex items-center justify-center text-[11px] font-bold">
                    {{ auth()->user()->getInitials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-500 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <a href="{{ route('account') }}" class="block px-4 py-2 text-[11px] text-gray-400 hover:text-white transition">&larr; {{ __('Back to Store') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block w-full text-left px-4 py-2 text-[11px] text-gray-400 hover:text-white transition">{{ __('Sign Out') }}</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-30">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-bold">@yield('header', __('Security Dashboard'))</h1>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[11px] text-gray-400">{{ __('System Status') }}</p>
                        <p class="text-[12px] font-semibold text-gray-700 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-gray-900"></span> {{ __('All Systems Operational') }}
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash -->
        @if(session('success'))
            <div class="bg-gray-100 border-b border-gray-300 text-gray-800 px-8 py-3 text-[13px]">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-gray-900 border-b border-gray-700 text-white px-8 py-3 text-[13px]">{{ session('error') }}</div>
        @endif

        <main class="p-8">@yield('content')</main>
    </div>

    @stack('scripts')
</body>
</html>
