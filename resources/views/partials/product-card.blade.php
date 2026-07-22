<div class="product-card group relative bg-white">
    <a href="{{ route('shop.show', $product) }}" class="block relative overflow-hidden rounded-xl bg-surface-muted aspect-square">
        <img src="{{ $product->primaryImage() }}" alt="{{ $product->name }}" class="product-image-primary absolute inset-0 w-full h-full object-cover transition-opacity duration-500">
        <img src="{{ $product->secondaryImage() }}" alt="{{ $product->name }}" class="product-image-secondary absolute inset-0 w-full h-full object-cover transition-opacity duration-500">

        @if($product->hasDiscount())
            <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 uppercase tracking-wider">-{{ $product->discountPercent() }}%</span>
        @endif
        @if($product->is_new)
            <span class="absolute top-3 right-3 bg-brand text-white text-[10px] font-bold px-2.5 py-1 uppercase tracking-wider">{{ __('New') }}</span>
        @endif
        @if($product->is_clearance)
            <span class="absolute top-3 right-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 uppercase tracking-wider">{{ __('Sale') }}</span>
        @endif

        <div class="product-overlay absolute inset-0 bg-black/10 transition-opacity duration-300"></div>

        <form action="{{ route('cart.add') }}" method="POST" class="absolute bottom-0 left-0 right-0 p-3 product-overlay transition-opacity duration-300">
            @csrf
            <input type="hidden" name="slug" value="{{ $product->slug }}">
            <button type="submit" class="w-full bg-brand text-white py-3 text-[11px] font-bold uppercase tracking-[0.15em] hover:bg-accent hover:text-brand transition-all duration-300">
                {{ __('Add to Cart') }}
            </button>
        </form>
    </a>

    <div class="mt-3.5 px-0.5">
        <a href="{{ route('shop.show', $product) }}" class="block">
            <h3 class="text-[13px] font-semibold leading-snug line-clamp-2 tracking-[-0.01em] group-hover:opacity-60 transition-opacity">{{ $product->name }}</h3>
        </a>
        <div class="mt-1.5 flex items-center gap-2">
            @if($product->hasDiscount())
                <span class="text-[13px] font-bold">${{ number_format($product->price, 2) }}</span>
                <span class="text-[12px] text-gray-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
            @else
                <span class="text-[13px] font-bold">${{ number_format($product->price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
