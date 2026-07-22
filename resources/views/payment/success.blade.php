@extends('layouts.app')

@section('title', @lang('Payment Confirmed') . ' - YeaBneh Store')

@section('content')

<section class="py-16 min-h-[70vh] flex items-center">
    <div class="max-w-[700px] mx-auto px-5 w-full">
        <div class="text-center mb-10">
            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-3">{{ __('Order Confirmed') }}</h1>
            <p class="text-gray-500 text-[14px]">{{ __('Thank you for your purchase! Your order is being processed.') }}</p>
        </div>

        <!-- Order Summary -->
        <div class="border border-gray-100 p-8 mb-8">
            <div class="grid grid-cols-2 gap-y-4 gap-x-8 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Order Number') }}</span>
                    <span class="text-[16px] font-extrabold">{{ $order->order_number }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Total') }}</span>
                    <span class="text-[16px] font-extrabold">${{ number_format($order->total, 2) }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Payment Method') }}</span>
                    <span class="text-[14px] font-semibold">{{ $payment->methodName() }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Payment Status') }}</span>
                    <span class="inline-block px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $payment->statusColor() }}">{{ $payment->status }}</span>
                </div>
            </div>

            <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] mb-4">{{ __('Items') }}</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 bg-surface-muted shrink-0 overflow-hidden border border-gray-100">
                            <img src="{{ $item->product?->primaryImage() ?? 'https://placehold.co/96x96?text=YeaBneh' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-[13px] font-semibold">{{ $item->product_name }}</p>
                            <p class="text-[12px] text-gray-400">{{ __('Qty') }}: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-[13px] font-bold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-[12px] font-bold uppercase tracking-[0.15em] mb-3">{{ __('Shipping To') }}</h3>
                <p class="text-[13px] text-gray-600">{{ $order->customer_name }}</p>
                <p class="text-[13px] text-gray-600">{{ $order->shipping_address }}</p>
                <p class="text-[13px] text-gray-600">{{ $order->customer_email }}</p>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 p-6 mb-8">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-[13px] font-semibold text-blue-800 mb-1">{{ __("What's Next?") }}</p>
                    <p class="text-[12px] text-blue-700 leading-relaxed">
                        {{ __("We'll send a confirmation email to") }} <strong>{{ $order->customer_email }}</strong> {{ __('once your payment is verified.') }}
                        {{ __('Your order will be shipped within 2-5 business days after payment confirmation.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center space-x-4">
            <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-8 py-3.5 text-[12px] font-bold uppercase tracking-[0.15em]">
                {{ __('Continue Shopping') }}
            </a>
        </div>
    </div>
</section>

@endsection
