@extends('layouts.app')

@section('title', 'Shop - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-extrabold uppercase tracking-[-0.02em]">ሁሉንም ይገበያዩ</h1>
            <p class="text-gray-400 mt-2 text-[14px]">{{ $products->total() }} ምርቶች</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar -->
            <aside class="lg:w-60 shrink-0">
                <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
                    <div class="mb-8">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ፈልግ..."
                            class="w-full border border-gray-200 px-4 py-3 text-[13px] focus:outline-none focus:border-brand transition-colors rounded-lg placeholder:text-gray-400">
                    </div>
                    <div class="mb-8">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-4">ምድር</h3>
                        <div class="space-y-2.5">
                            <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="accent-black">
                                <span class="group-hover:opacity-60 transition">ሁሉም</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2.5 text-[13px] cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'checked' : '' }} onchange="this.form.submit()" class="accent-black">
                                    <span class="group-hover:opacity-60 transition">{{ $cat->name }} <span class="text-gray-400">({{ $cat->products_count }})</span></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-4">ደርድር በ</h3>
                        <select name="sort" onchange="this.form.submit()" class="w-full border border-gray-200 px-4 py-3 text-[13px] focus:outline-none focus:border-brand transition-colors rounded-lg">
                            <option value="">ቆንጆ</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>ዋጋ: ዝቅተኛ እስከ ከፍተኛ</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>ዋጋ: ከፍተኛ እስከ ዝቅተኛ</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>አዲስ</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>ስም</option>
                        </select>
                    </div>
                </form>
            </aside>

            <!-- Products -->
            <div class="flex-1">
                @if($products->count())
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="mt-14">{{ $products->withQueryString()->links('partials.pagination') }}</div>
                @else
                    <div class="text-center py-24">
                        <p class="text-lg text-gray-400 mb-4">ምርት አልተገኘም.</p>
                        <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">ማጣሪያዎችን ያጽዱ</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
