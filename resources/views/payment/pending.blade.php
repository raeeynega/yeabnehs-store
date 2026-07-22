@extends('layouts.app')

@section('title', @lang('Payment Pending') . ' - YeaBneh Store')

@section('content')

<section class="py-16 min-h-[70vh] flex items-center">
    <div class="max-w-[700px] mx-auto px-5 w-full">
        <div class="text-center mb-10">
            <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-3">{{ __('Payment Under Review') }}</h1>
            <p class="text-gray-500 text-[14px]">{{ __("We've received your payment confirmation") }}</p>
        </div>

        <!-- Payment Details Card -->
        <div class="border border-gray-100 p-8 mb-8">
            <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                <div class="col-span-2 flex justify-between items-center pb-4 border-b border-gray-100">
                    <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Payment Number') }}</span>
                    <span class="text-[14px] font-semibold">{{ $payment->payment_number }}</span>
                </div>
                <div class="py-3 border-b border-gray-100">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Order') }}</span>
                    <span class="text-[14px] font-semibold">{{ $order->order_number }}</span>
                </div>
                <div class="py-3 border-b border-gray-100">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Method') }}</span>
                    <span class="text-[14px] font-semibold">{{ $payment->methodName() }}</span>
                </div>
                <div class="py-3 border-b border-gray-100">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Amount') }}</span>
                    <span class="text-[14px] font-bold">${{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="py-3 border-b border-gray-100">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Status') }}</span>
                    <span class="inline-block px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $payment->statusColor() }}">{{ $payment->status }}</span>
                </div>
                @if($payment->transaction_ref)
                <div class="py-3 border-b border-gray-100">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ $payment->method === 'cbe' ? __('Reference Number') : __('Tx ID') }}</span>
                    <span class="text-[14px] font-semibold">{{ $payment->transaction_ref }}</span>
                </div>
                @endif
                <div class="py-3">
                    <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-400 mb-1">{{ __('Submitted') }}</span>
                    <span class="text-[14px] font-semibold">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 p-6 mb-8">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-[13px] font-semibold text-blue-800 mb-1">{{ __('What happens next?') }}</p>
                    <p class="text-[12px] text-blue-700 leading-relaxed">{{ __('Our team will verify your payment within') }} <strong>1-2 {{ __('business hours') }}</strong>. {{ __("You'll receive an email at") }} <strong>{{ $order->customer_email }}</strong> {{ __('once your payment is confirmed and your order is being processed.') }}</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="btn-primary inline-block px-8 py-3.5 text-[12px] font-bold uppercase tracking-[0.15em]">
                {{ __('Continue Shopping') }}
            </a>
        </div>
    </div>
</section>

@endsection
