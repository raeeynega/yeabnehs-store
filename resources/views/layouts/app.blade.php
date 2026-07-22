<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name') . ' - Premium Fitness Gear')</title>
    <meta name="description" content="@yield('description', 'Premium weight vests, parallettes, resistance bands, and training apparel designed for peak performance.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { DEFAULT: '#0a0a0a', light: '#1a1a1a', muted: '#2a2a2a' },
                        accent: { DEFAULT: '#c8ff00', dark: '#a8d900' },
                        surface: { DEFAULT: '#fafafa', muted: '#f5f5f5' },
                    }
                }
            }
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 25s linear infinite; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.8s ease-out forwards; }
        .fade-up-delay { animation: fadeUp 0.8s ease-out 0.15s forwards; opacity: 0; }
        .fade-up-delay-2 { animation: fadeUp 0.8s ease-out 0.3s forwards; opacity: 0; }

        .product-card { transition: transform 0.3s ease; }
        .product-card:hover { transform: translateY(-4px); }
        .product-card:hover .product-image-primary { opacity: 0; }
        .product-card .product-image-secondary { opacity: 0; }
        .product-card:hover .product-image-secondary { opacity: 1; }
        .product-card .product-overlay { opacity: 0; }
        .product-card:hover .product-overlay { opacity: 1; }

        .hero-gradient {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
        }

        .section-fade { transition: opacity 0.6s ease, transform 0.6s ease; }

        .btn-primary {
            background: #0a0a0a; color: #fff;
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .btn-primary:hover { background: #c8ff00; color: #0a0a0a; }

        .btn-outline {
            border: 1.5px solid #0a0a0a; color: #0a0a0a;
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .btn-outline:hover { background: #0a0a0a; color: #fff; }

        .btn-accent {
            background: #c8ff00; color: #0a0a0a;
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .btn-accent:hover { background: #a8d900; }

        ::selection { background: #c8ff00; color: #0a0a0a; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-brand font-sans antialiased">

    <!-- Announcement Bar -->
    <div class="bg-brand text-white overflow-hidden">
        <div class="py-2.5">
            <div class="animate-marquee whitespace-nowrap inline-block text-[11px] font-medium tracking-[0.25em] uppercase">
                <span class="mx-10 opacity-70">ለአፈፃፀም የተሰራ</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">ያልተመሳሰለ መሳሪያ ጥራት</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">#1 ዓለም አቀፍ የቅርጽ እንቅስቃሴ</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">ከ$100 በላይ ነፃ መላኪያ</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">ለአፈፃፀም የተሰራ</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">ያልተመሳሰለ መሳሪያ ጥራት</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">#1 ዓለም አቀፍ የቅርጽ እንቅስቃሴ</span>
                <span class="mx-10 text-accent">&#9679;</span>
                <span class="mx-10 opacity-70">ከ$100 በላይ ነፃ መላኪያ</span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-black/5 transition-all">
        <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Mobile Menu -->
                <button id="mobileMenuBtn" class="lg:hidden p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-xl font-extrabold tracking-[-0.04em] uppercase">YeaBneh</span>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center space-x-8 text-[12px] font-semibold uppercase tracking-[0.15em]">
                    <a href="{{ route('shop.index') }}" class="hover:opacity-50 transition-opacity duration-200">ሁሉንም ይገበያዩ</a>
                    <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="hover:opacity-50 transition-opacity duration-200">መሳሪያዎች</a>
                    <a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="hover:opacity-50 transition-opacity duration-200">አልባሶች</a>
                    <a href="{{ route('courses.index') }}" class="hover:opacity-50 transition-opacity duration-200">ኮርሶች</a>
                    <a href="{{ route('training.index') }}" class="hover:opacity-50 transition-opacity duration-200">ስልጠና</a>
                    <a href="{{ route('shop.index', ['clearance' => 1]) }}" class="hover:opacity-50 transition-opacity duration-200 text-red-600">ሽያጭ</a>
                </nav>

                <!-- Right Icons -->
                <div class="flex items-center gap-5">
                    <!-- Language Switcher -->
                    <div class="relative" id="langSwitcher">
                        <button onclick="document.getElementById('langDropdown').classList.toggle('hidden')" class="hover:opacity-50 transition-opacity duration-200 text-[11px] font-bold uppercase tracking-[0.15em]">
                            {{ strtoupper(app()->getLocale()) }}
                        </button>
                        <div id="langDropdown" class="hidden absolute right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl py-2 min-w-[120px] z-50">
                            <a href="{{ route('locale.switch', 'am') }}" class="block px-4 py-2 text-[12px] font-medium hover:bg-gray-50 transition {{ app()->getLocale() === 'am' ? 'text-brand font-bold' : 'text-gray-500' }}">አማርኛ</a>
                            <a href="{{ route('locale.switch', 'om') }}" class="block px-4 py-2 text-[12px] font-medium hover:bg-gray-50 transition {{ app()->getLocale() === 'om' ? 'text-brand font-bold' : 'text-gray-500' }}">Afaan Oromoo</a>
                            <a href="{{ route('locale.switch', 'en') }}" class="block px-4 py-2 text-[12px] font-medium hover:bg-gray-50 transition {{ app()->getLocale() === 'en' ? 'text-brand font-bold' : 'text-gray-500' }}">English</a>
                        </div>
                    </div>
                    <a href="{{ route('shop.index') }}" class="hover:opacity-50 transition-opacity duration-200">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </a>
                    @auth
                        <a href="{{ route('account') }}" class="hover:opacity-50 transition-opacity duration-200 relative">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="w-[18px] h-[18px] rounded-full object-cover">
                            @else
                                <div class="w-[18px] h-[18px] rounded-full bg-brand text-white flex items-center justify-center text-[8px] font-bold">
                                    {{ Auth::user()->getInitials() }}
                                </div>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hover:opacity-50 transition-opacity duration-200">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="hover:opacity-50 transition-opacity duration-200 relative">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @php $cartCount = array_sum(session()->get('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-brand text-white text-[9px] font-bold w-[18px] h-[18px] rounded-full flex items-center justify-center leading-none">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Slide-in Sidebar -->
    <div id="mobileMenu" class="fixed inset-0 z-[100] hidden" style="visibility: hidden;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300" id="mobileMenuOverlay" style="opacity: 0;"></div>
        <div class="absolute left-0 top-0 bottom-0 w-[340px] max-w-[85vw] bg-white shadow-2xl flex flex-col transform -translate-x-full transition-transform duration-300 ease-out" id="mobileMenuPanel">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <span class="text-lg font-extrabold tracking-[-0.04em] uppercase">YeaBneh</span>
                <button id="mobileMenuClose" class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 border-b border-gray-100">
                <form action="{{ route('shop.index') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="ምርቶችን ፈልግ..." class="w-full border border-gray-200 rounded-xl px-4 py-3 pl-10 text-[13px] focus:outline-none focus:border-brand transition-colors">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>
            <nav class="flex-1 overflow-y-auto p-6 space-y-0.5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                    <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    ዋና ገጽ
                </a>
                <a href="{{ route('shop.index') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                    <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    ሁሉንም ይገበያዩ
                </a>
                <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="flex items-center gap-3 py-3 pl-[34px] text-[12px] font-medium text-gray-500 hover:text-black transition">
                    መሳሪያዎች
                </a>
                <a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="flex items-center gap-3 py-3 pl-[34px] text-[12px] font-medium text-gray-500 hover:text-black transition">
                    አልባሶች
                </a>
                <a href="{{ route('shop.index', ['clearance' => 1]) }}" class="flex items-center gap-3 py-3 pl-[34px] text-[12px] font-medium text-red-500 hover:text-red-700 transition">
                    ሽያጭ
                </a>
                <a href="{{ route('courses.index') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                    <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    ኮርሶች
                </a>
                <a href="{{ route('training.index') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                    <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    ስልጠና
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                    <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    ጋርዣ
                    @php $cartCount = array_sum(session()->get('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="ml-auto bg-brand text-white text-[9px] font-bold w-5 h-5 rounded-full flex items-center justify-center leading-none">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="h-px bg-gray-100 my-4"></div>
                <!-- Language Switcher Mobile -->
                <div class="py-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-3">Language</p>
                    <div class="flex gap-2">
                        <a href="{{ route('locale.switch', 'am') }}" class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-lg transition {{ app()->getLocale() === 'am' ? 'bg-brand text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">አማ</a>
                        <a href="{{ route('locale.switch', 'om') }}" class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-lg transition {{ app()->getLocale() === 'om' ? 'bg-brand text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">OMO</a>
                        <a href="{{ route('locale.switch', 'en') }}" class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-brand text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">EN</a>
                    </div>
                </div>
                <div class="h-px bg-gray-100 my-4"></div>
                @auth
                    <a href="{{ route('account') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                        <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        የእኔ መለያ
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] text-accent-dark hover:text-black transition">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            የደህንነት ዳሽቦርድ
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition text-left">
                            <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            ይውጡ
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                        <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        ይግቡ
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-3 py-3 text-[13px] font-semibold uppercase tracking-[0.12em] hover:text-gray-500 transition">
                        <svg class="w-[18px] h-[18px] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        መለያ ይፍጠሩ
                    </a>
                @endauth
            </nav>
            <div class="p-6 border-t border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold text-center">&copy; {{ date('Y') }} YeaBneh Store</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-accent/10 border-b border-accent/20 text-brand px-4 py-3 text-sm text-center font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-200 text-red-700 px-4 py-3 text-sm text-center font-medium">
            {{ session('error') }}
        </div>
    @endif

    <main>@yield('content')</main>

    <!-- Footer -->
    <footer class="bg-brand text-white mt-24">
        <!-- Top Section -->
        <div class="max-w-[1680px] mx-auto px-5 lg:px-10 pt-20 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
                <!-- Brand -->
                <div class="lg:col-span-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center mb-6">
                        <span class="text-2xl font-extrabold tracking-[-0.04em] uppercase">YeaBneh</span>
                    </a>
                    <p class="text-gray-500 text-[13px] leading-relaxed max-w-xs mb-8">
                        ለምርጥ አቋም የሚፈልጉ አትሌቶች የተነሳ ፍራይ የቅርጽ መሳሪያዎች። የትም ሆኑ ይለማመዱ፣ ሁሉንም ያሸንፉ።
                    </p>
                    <div class="flex items-center gap-5">
                        <a href="#" class="w-9 h-9 rounded-full border border-white/15 flex items-center justify-center hover:bg-white hover:text-brand transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full border border-white/15 flex items-center justify-center hover:bg-white hover:text-brand transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full border border-white/15 flex items-center justify-center hover:bg-white hover:text-brand transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="lg:col-span-2 lg:col-start-6">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">ሽያጭ</h4>
                    <ul class="space-y-3 text-[13px] text-gray-400">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">ሁሉም ምርቶች</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="hover:text-white transition-colors">መሳሪያዎች</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="hover:text-white transition-colors">አልባሶች</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-white transition-colors">ኮርሶች</a></li>
                        <li><a href="{{ route('training.index') }}" class="hover:text-white transition-colors">ስልጠና</a></li>
                        <li><a href="{{ route('shop.index', ['clearance' => 1]) }}" class="hover:text-white transition-colors">ሽያጭ</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">ኩባንያ</h4>
                    <ul class="space-y-3 text-[13px] text-gray-400">
                        <li><a href="{{ route('page.show', 'faq') }}" class="hover:text-white transition-colors">የተደጋጋሚ ጥያቄ</a></li>
                        <li><a href="{{ route('page.show', 'contact-us') }}" class="hover:text-white transition-colors">ያግኙን</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ትዕዛዝ ይከታተሉ</a></li>
                        <li><a href="{{ route('page.show', 'return-policy') }}" class="hover:text-white transition-colors">መመለሻ</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">ሕጋዊ</h4>
                    <ul class="space-y-3 text-[13px] text-gray-400">
                        <li><a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-white transition-colors">የግላዊነት ፖሊሲ</a></li>
                        <li><a href="{{ route('page.show', 'terms-and-conditions') }}" class="hover:text-white transition-colors">የአገልግሎት ውሎች</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">በቅርብ ይከተሉን</h4>
                    <p class="text-[13px] text-gray-500 mb-4">ለአዲስ ምርቶች፣ ሽያጭ እና የስልጠና ምክሮች ቅድመ ተቀባይነት ያግኙ።</p>
                    <form class="flex">
                        <input type="email" placeholder="ኢሜይልዎ" class="flex-1 bg-white/5 border border-white/10 px-4 py-3 text-[13px] text-white placeholder-gray-600 focus:outline-none focus:border-white/30 transition">
                        <button type="submit" class="bg-white text-brand px-5 py-3 text-[11px] font-bold uppercase tracking-widest hover:bg-accent transition-colors duration-300">ይቀላቀሉ</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/5">
            <div class="max-w-[1680px] mx-auto px-5 lg:px-10 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[12px] text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}. ሁሉም መብቶች የተጠበቁ ናቸው።</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-[12px] text-gray-600 hover:text-white transition-colors">ግላዊነት</a>
                    <a href="#" class="text-[12px] text-gray-600 hover:text-white transition-colors">ውሎች</a>
                    <a href="#" class="text-[12px] text-gray-600 hover:text-white transition-colors">ኩኪዎች</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const menuPanel = document.getElementById('mobileMenuPanel');
        const menuClose = document.getElementById('mobileMenuClose');
        const menuOverlay = document.getElementById('mobileMenuOverlay');

        function openSidebar() {
            menu.style.visibility = 'visible';
            menu.classList.remove('hidden');
            requestAnimationFrame(() => {
                menuOverlay.style.opacity = '1';
                menuPanel.style.transform = 'translateX(0)';
            });
        }

        function closeSidebar() {
            menuOverlay.style.opacity = '0';
            menuPanel.style.transform = 'translateX(-100%)';
            setTimeout(() => {
                menu.classList.add('hidden');
                menu.style.visibility = 'hidden';
            }, 300);
        }

        menuBtn?.addEventListener('click', openSidebar);
        menuClose?.addEventListener('click', closeSidebar);
        menuOverlay?.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                closeSidebar();
            }
        });

        // Close language dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const switcher = document.getElementById('langSwitcher');
            const dropdown = document.getElementById('langDropdown');
            if (switcher && dropdown && !switcher.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
