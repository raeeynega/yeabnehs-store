@extends('layouts.app')

@section('title', @lang('Select Payment Method') . ' - YeaBneh Store')

@section('content')

<section class="py-16 min-h-[70vh] flex items-center">
    <div class="max-w-[800px] mx-auto px-5 w-full">
        <div class="text-center mb-10">
            <div class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-accent-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-3">{{ __('Select Payment Method') }}</h1>
            <p class="text-gray-500 text-[14px]">{{ __('Order') }} <strong>{{ $order->order_number }}</strong> &mdash; {{ __('Total') }}: <strong>${{ number_format($order->total, 2) }}</strong></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- CBE -->
            <form action="{{ route('payment.instructions', $order->order_number) }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="cbe">
                <button type="submit" class="w-full group border-2 border-gray-200 hover:border-brand p-8 text-left transition-all duration-300 hover:shadow-lg">
                    <div class="w-14 h-14 rounded-xl bg-[#003d7c] flex items-center justify-center mb-5">
                        <span class="text-white font-extrabold text-[18px]">CBE</span>
                    </div>
                    <h3 class="text-lg font-bold mb-1.5">Commercial Bank of Ethiopia</h3>
                    <p class="text-[13px] text-gray-500 mb-4 leading-relaxed">{{ __('Transfer directly to our CBE bank account. You will receive the account number and instructions in the next step.') }}</p>
                    <div class="flex items-center gap-2 text-[12px] font-semibold text-brand group-hover:text-accent-dark transition-colors">
                        <span>{{ __('Continue with CBE') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            </form>

            <!-- Telebirr -->
            <form action="{{ route('payment.instructions', $order->order_number) }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="telebirr">
                <button type="submit" class="w-full group border-2 border-gray-200 hover:border-brand p-8 text-left transition-all duration-300 hover:shadow-lg">
                    <div class="w-14 h-14 rounded-xl bg-[#ff6b00] flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-1.5">Telebirr</h3>
                    <p class="text-[13px] text-gray-500 mb-4 leading-relaxed">{{ __('Pay via Telebirr mobile money. Send money from your phone number to our merchant account.') }}</p>
                    <div class="flex items-center gap-2 text-[12px] font-semibold text-brand group-hover:text-accent-dark transition-colors">
                        <span>{{ __('Continue with Telebirr') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('shop.index') }}" class="text-[12px] font-semibold uppercase tracking-[0.12em] text-gray-400 hover:text-brand transition-colors">
                &larr; {{ __('Back to Store') }}
            </a>
        </div>
    </div>
</section>

@endsection
