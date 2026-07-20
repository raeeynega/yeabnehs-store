@extends('layouts.app')

@section('title', $product->name . ' - YeaBneh Store')

@section('content')

<section class="py-12">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <!-- Breadcrumb -->
        <nav class="text-[11px] text-gray-400 mb-10 uppercase tracking-[0.15em] font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand transition">ዋና ገጽ</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-brand transition">ሽያጭ</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-brand transition">{{ $product->category->name }}</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <span class="text-brand">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20">
            <!-- Images -->
            <div>
                <div class="bg-surface-muted aspect-square flex items-center justify-center overflow-hidden rounded-xl" id="mainImage">
                    <img src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3 mt-4">
                        @foreach($product->images as $img)
                            <button onclick="document.getElementById('mainImage').querySelector('img').src='{{ $img->image_path }}'"
                                class="bg-surface-muted aspect-square overflow-hidden rounded-lg border-2 {{ $loop->first ? 'border-brand' : 'border-transparent hover:border-gray-300' }} transition-colors">
                                <img src="{{ $img->image_path }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="lg:py-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">{{ $product->category->name }}</p>
                <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-6 leading-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mb-8">
                    @if($product->hasDiscount())
                        <span class="text-3xl font-extrabold">${{ number_format($product->price, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                        <span class="bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 uppercase tracking-wider">-{{ $product->discountPercent() }}%</span>
                    @else
                        <span class="text-3xl font-extrabold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->description)
                    <div class="text-[14px] text-gray-500 leading-relaxed mb-10">
                        {!! $product->description !!}
                    </div>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="mb-10">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $product->slug }}">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <button type="button" onclick="this.nextElementSibling.value = Math.max(1, parseInt(this.nextElementSibling.value) - 1)" class="w-12 h-12 flex items-center justify-center text-lg hover:bg-surface-muted transition">-</button>
                            <input type="number" name="qty" value="1" min="1" class="w-14 text-center border-x border-gray-200 h-12 text-[14px] font-medium focus:outline-none">
                            <button type="button" onclick="this.previousElementSibling.value = parseInt(this.previousElementSibling.value) + 1" class="w-12 h-12 flex items-center justify-center text-lg hover:bg-surface-muted transition">+</button>
                        </div>
                        <button type="submit" class="flex-1 btn-primary h-12 text-[12px] font-bold uppercase tracking-[0.15em]">
                            ወደ ጋርዣ ያክሉ
                        </button>
                    </div>
                </form>

                <div class="border-t border-gray-100 pt-8 space-y-3.5 text-[13px] text-gray-500">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        ከ $100 በላይ በሆነ ትዕዛዝ ላይ ነፃ መላኪያ
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        30 ቀን የመመለሻ ፖሊሲ
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        ደህንነቱ የተጠበቀ ክፍያ
                    </div>
                </div>
            </div>
        </div>

        @if($relatedProducts->count())
            <div class="mt-24">
                <h2 class="text-2xl font-extrabold uppercase tracking-[-0.02em] mb-10">እርስዎም ሊወዱት ይችላሉ</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-5">
                    @foreach($relatedProducts as $rp)
                        @include('partials.product-card', ['product' => $rp])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
