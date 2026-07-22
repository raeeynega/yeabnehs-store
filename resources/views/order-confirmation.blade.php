@extends('layouts.app')

@section('title', __('Order Confirmed') . ' - ' . __('YeaBneh Store'))

@section('content')

<section class="py-16">
    <div class="max-w-xl mx-auto px-5 text-center">
        <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold uppercase tracking-[-0.02em] mb-4">{{ __('Order Confirmed!') }}</h1>
        <p class="text-gray-500 mb-2 text-[15px]">{{ __('Thank you for your order.') }}</p>
        <p class="text-[13px] text-gray-400 mb-10">{{ __('Order Number') }}: <span class="font-bold text-brand">{{ $order->order_number }}</span></p>

        <div class="bg-surface-muted p-7 text-left space-y-3.5 mb-8">
            <div class="flex justify-between text-[14px]">
                <span class="text-gray-500">{{ __('Status') }}</span>
                <span class="font-bold capitalize">{{ $order->status }}</span>
            </div>
            <div class="flex justify-between text-[14px]">
                <span class="text-gray-500">{{ __('Payment') }}</span>
                <span class="font-bold capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-[14px]">
                <span class="text-gray-500">{{ __('Subtotal') }}</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-[14px]">
                <span class="text-gray-500">{{ __('Shipping') }}</span>
                <span>{{ $order->shipping_cost == 0 ? __('Free') : '$' . number_format($order->shipping_cost, 2) }}</span>
            </div>
            <div class="h-px bg-gray-200"></div>
            <div class="flex justify-between text-lg">
                <span class="font-bold">{{ __('Total') }}</span>
                <span class="font-extrabold">${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="bg-surface-muted p-7 text-left mb-8">
            <h3 class="text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-4">{{ __('Items') }}</h3>
            @foreach($order->items as $item)
                <div class="flex justify-between text-[14px] py-2.5 border-b border-gray-200 last:border-0">
                    <span class="font-medium">{{ $item->product_name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                    <span class="font-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
        </div>

        <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
            {{ __('Continue Shopping') }}
        </a>
    </div>
</section>

@endsection
