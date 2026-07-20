@extends('layouts.app')

@section('title', 'YeaBneh Store - Premium Fitness Gear')

@section('content')

<!-- Hero -->
<section class="relative hero-gradient overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-accent rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 right-1/4 w-72 h-72 bg-white rounded-full blur-[100px]"></div>
    </div>
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10 relative">
        <div class="min-h-[80vh] flex items-center py-20">
            <div class="max-w-3xl">
                <p class="text-accent text-[11px] font-bold uppercase tracking-[0.3em] mb-6 fade-up">YeaBneh መሳሪያዎች</p>
                <h1 class="text-5xl sm:text-6xl lg:text-[5.5rem] font-extrabold text-white leading-[0.95] tracking-[-0.03em] uppercase mb-8 fade-up">
                    አዲስ ቅርጽ.<br>
                    <span class="text-accent">ተጨማሪ ኃይል.</span><br>
                    ተጨማሪ ቁርጠኝነት.
                </h1>
                <p class="text-gray-400 text-lg font-light mb-10 max-w-lg fade-up-delay">ሁሉም በእርስዎ እጅ። ለደረጃ የሚቀጥጡ አትሌቶች የተነሳ ፍራይ መሳሪያ።</p>
                <div class="flex flex-wrap gap-4 fade-up-delay-2">
                    <a href="{{ route('shop.index') }}" class="btn-accent px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                        አሁን ይግዙ
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="border border-white/20 text-white px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em] hover:bg-white hover:text-brand transition-all duration-300">
                        Equipment
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Marquee -->
<div class="bg-brand text-white overflow-hidden border-y border-white/5">
    <div class="py-4">
        <div class="animate-marquee whitespace-nowrap inline-block text-[11px] font-semibold uppercase tracking-[0.25em]">
            <span class="mx-10 text-white/40">ለአፈፃፀም የተሰራ</span>
            <span class="mx-10 text-accent">&#9679;</span>
            <span class="mx-10 text-white/40">ያልተመሳሰለ መሳሪያ ጥራት</span>
            <span class="mx-10 text-accent">&#9679;</span>
            <span class="mx-10 text-white/40">#1 ዓለም አቀፍ የቅርጽ እንቅስቃሴ</span>
            <span class="mx-10 text-accent">&#9679;</span>
            <span class="mx-10 text-white/40">ለአፈፃፀም የተሰራ</span>
            <span class="mx-10 text-accent">&#9679;</span>
            <span class="mx-10 text-white/40">ያልተመሳሰለ መሳሪያ ጥራት</span>
            <span class="mx-10 text-accent">&#9679;</span>
            <span class="mx-10 text-white/40">#1 ዓለም አቀፍ የቅርጽ እንቅስቃሴ</span>
        </div>
    </div>
</div>

<!-- Category Links -->
<section class="py-20">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="relative group overflow-hidden bg-brand aspect-[16/10]">
                <img src="https://placehold.co/900x560/1a1a1a/c8ff00?text=Men%27s+Collection" alt="Men's Collection" class="w-full h-full object-cover opacity-70 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="inline-block bg-accent text-brand px-5 py-2.5 text-[11px] font-bold uppercase tracking-[0.15em] group-hover:bg-white transition-colors duration-300">የወንዶች ስብስብ</span>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 'apparel']) }}" class="relative group overflow-hidden bg-brand aspect-[16/10]">
                <img src="https://placehold.co/900x560/1a1a1a/c8ff00?text=Women%27s+Collection" alt="Women's Collection" class="w-full h-full object-cover opacity-70 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="inline-block bg-accent text-brand px-5 py-2.5 text-[11px] font-bold uppercase tracking-[0.15em] group-hover:bg-white transition-colors duration-300">የሴቶች ስብስብ</span>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="relative group overflow-hidden bg-brand aspect-[16/10] sm:col-span-2 lg:col-span-1">
                <img src="https://placehold.co/900x560/1a1a1a/c8ff00?text=Elite+Equipment" alt="Equipment" class="w-full h-full object-cover opacity-70 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="inline-block bg-accent text-brand px-5 py-2.5 text-[11px] font-bold uppercase tracking-[0.15em] group-hover:bg-white transition-colors duration-300">ኤሊት መሳሪያዎች</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Equipment -->
@if($featuredProducts->count())
<section class="pb-20">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">Equipment</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em]">ለከፍተኛ አፈፃፀም ኤሊት መሳሪያዎች</h2>
            </div>
            <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="hidden lg:inline-block btn-outline px-8 py-3.5 text-[11px] font-bold uppercase tracking-[0.15em]">
                ሁሉንም ይግዙ
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
            @foreach($featuredProducts->take(8) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-10 text-center lg:hidden">
            <a href="{{ route('shop.index', ['category' => 'equipment']) }}" class="btn-outline inline-block px-8 py-3.5 text-[11px] font-bold uppercase tracking-[0.15em]">ሁሉንም መሳሪያዎች ይግዙ</a>
        </div>
    </div>
</section>
@endif

<!-- App Download -->
<section class="py-20 bg-surface">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10 text-center">
        <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">YeaBneh</p>
        <h2 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-4">የእኛን ማህበረሰብ ይቀላቀሉ</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-10 text-[15px]">በመብራት የሚሰሩ ሚሊዮኖች አትሌቶች። መተግበሪያውን ያውርዱ ጉዞዎን ይጀምሩ።</p>
        <div class="flex items-center justify-center gap-4">
            <a href="#" class="btn-primary px-8 py-4 text-[12px] font-bold uppercase tracking-[0.12em] rounded-lg">Google Play</a>
            <a href="#" class="btn-primary px-8 py-4 text-[12px] font-bold uppercase tracking-[0.12em] rounded-lg">App Store</a>
        </div>
    </div>
</section>

<!-- Clearance -->
@if($clearanceProducts->count())
<section class="py-20">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="bg-gradient-to-r from-red-600 to-red-500 p-8 lg:p-14 mb-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-red-200/70 mb-2">ከ50% በላይ ቅናሽ</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold uppercase text-white tracking-[-0.02em]">ቅናሽ ይግዙ</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
            @foreach($clearanceProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('shop.index', ['clearance' => 1]) }}" class="btn-outline inline-block px-8 py-3.5 text-[11px] font-bold uppercase tracking-[0.15em]">ሁሉንም ቅናሽ ይመልከቱ</a>
        </div>
    </div>
</section>
@endif

<!-- New Arrivals -->
@if($newArrivals->count())
<section class="py-20 bg-surface">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">አዲስ የወጣ</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em]">አዳዲስ መምጣቶች</h2>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="hidden lg:inline-block btn-outline px-8 py-3.5 text-[11px] font-bold uppercase tracking-[0.15em]">
                ሁሉንም ይመልከቱ
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
            @foreach($newArrivals as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
