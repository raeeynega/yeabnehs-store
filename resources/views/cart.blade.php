@extends('layouts.app')

@section('title', __('Cart') . ' - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-10">{{ __('Cart') }}</h1>

        @if(!empty($products))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        @foreach($products as $slug => $item)
                            <div class="flex gap-5 p-5 border border-gray-100 rounded-xl hover:border-gray-200 transition-colors">
                                <a href="{{ route('shop.show', $item['product']) }}" class="w-24 h-24 bg-surface-muted shrink-0 overflow-hidden rounded-lg">
                                    <img src="{{ $item['product']->primaryImage() }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('shop.show', $item['product']) }}" class="text-[14px] font-semibold hover:opacity-60 transition line-clamp-1">{{ $item['product']->name }}</a>
                                    <p class="text-[14px] font-bold mt-1.5">${{ number_format($item['product']->price, 2) }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-gray-200 rounded-lg">
                                            @csrf
                                            <input type="hidden" name="slug" value="{{ $slug }}">
                                            <button type="button" onclick="this.nextElementSibling.value = Math.max(0, parseInt(this.nextElementSibling.value) - 1); this.closest('form').submit();" class="w-8 h-8 flex items-center justify-center text-sm hover:bg-surface-muted transition">-</button>
                                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" class="w-12 text-center border-x border-gray-200 h-8 text-[13px] focus:outline-none" onchange="this.closest('form').submit();">
                                            <button type="button" onclick="this.previousElementSibling.value = parseInt(this.previousElementSibling.value) + 1; this.closest('form').submit();" class="w-8 h-8 flex items-center justify-center text-sm hover:bg-surface-muted transition">+</button>
                                        </form>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="slug" value="{{ $slug }}">
                                            <button type="submit" class="text-[11px] text-gray-400 hover:text-red-600 uppercase tracking-[0.15em] font-medium transition">{{ __('Remove') }}</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[14px] font-bold">${{ number_format($item['product']->price * $item['qty'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-surface-muted p-7 rounded-xl sticky top-24">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">{{ __('Order Summary') }}</h2>
                        <div class="space-y-3 text-[14px]">
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('Subtotal') }}</span>
                                <span class="font-bold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('Shipping') }}</span>
                                <span class="font-bold">{{ $subtotal >= 100 ? __('Free') : '$9.99' }}</span>
                            </div>
                            <div class="h-px bg-gray-200 my-2"></div>
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">{{ __('Total') }}</span>
                                <span class="font-extrabold">${{ number_format($subtotal + ($subtotal >= 100 ? 0 : 9.99), 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="block mt-6 btn-primary py-4 text-center text-[12px] font-bold uppercase tracking-[0.15em]">
                            {{ __('Proceed to Checkout') }}
                        </a>
                        <a href="{{ route('shop.index') }}" class="block mt-3 text-center text-[13px] text-gray-400 hover:text-brand transition">
                            {{ __('Keep Shopping') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-24">
                <svg class="w-16 h-16 mx-auto text-gray-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="text-xl text-gray-400 mb-6">{{ __('Your cart is empty') }}</p>
                <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em]">{{ __('Start Shopping') }}</a>
            </div>
        @endif
    </div>
</section>

@endsection
