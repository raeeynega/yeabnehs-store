@extends('layouts.app')

@section('title', @lang('Payment Instructions') . ' - YeaBneh Store')

@section('content')

<section class="py-16 min-h-[70vh]">
    <div class="max-w-[700px] mx-auto px-5">
        <div class="text-center mb-10">
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-3">{{ __('Payment Instructions') }}</h1>
            <p class="text-gray-500 text-[14px]">{{ __('Order') }} <strong>{{ $order->order_number }}</strong> &mdash; <strong>ETB {{ number_format($order->total, 2) }}</strong></p>
        </div>

        <!-- Mobile App Launch Banner -->
        <div id="mobileAppBanner" class="hidden mb-8">
            @if($method === 'telebirr')
                <div class="bg-[#ff6b00] text-white p-5 rounded-xl flex items-center gap-4 shadow-lg shadow-[#ff6b00]/20">
                    <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[15px]">{{ __('Pay with Telebirr') }}</p>
                        <p class="text-white/80 text-[12px]">{{ __('Tap below to open the Telebirr app') }}</p>
                    </div>
                    <a href="telebirr://" id="telebirrLaunch"
                       onclick="launchApp(this, 'telebirr')"
                       class="bg-white text-[#ff6b00] px-5 py-3 rounded-lg text-[12px] font-bold uppercase tracking-wider shrink-0 active:scale-95 transition-transform">
                        {{ __('Open App') }}
                    </a>
                </div>
                <p id="telebirrFallback" class="hidden text-center mt-2 text-[11px] text-gray-400">
                    {{ __('App not installed?') }} <a href="tel:*127%23" class="text-brand font-semibold underline">{{ __('Dial *127#') }}</a> {{ __('or pay manually below') }}
                </p>
            @elseif($method === 'cbe')
                <div class="bg-[#003d7c] text-white p-5 rounded-xl flex items-center gap-4 shadow-lg shadow-[#003d7c]/20">
                    <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-[16px]">CBE</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[15px]">{{ __('Pay with CBE Mobile') }}</p>
                        <p class="text-white/80 text-[12px]">{{ __('Tap below to open CBE mobile banking') }}</p>
                    </div>
                    <a href="cbeebirr://" id="cbeLaunch"
                       onclick="launchApp(this, 'cbe')"
                       class="bg-white text-[#003d7c] px-5 py-3 rounded-lg text-[12px] font-bold uppercase tracking-wider shrink-0 active:scale-95 transition-transform">
                        {{ __('Open App') }}
                    </a>
                </div>
                <p id="cbeFallback" class="hidden text-center mt-2 text-[11px] text-gray-400">
                    {{ __('App not installed? Pay manually using the account details below') }}
                </p>
            @endif
        </div>

        <!-- Account Details -->
        <div class="border border-gray-100 p-8 mb-8">
            @if($method === 'cbe')
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-xl bg-[#003d7c] flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-[18px]">CBE</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('Bank Transfer - CBE') }}</h2>
                        <p class="text-[13px] text-gray-500">{{ __('Transfer the exact amount to the account below') }}</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Bank Name') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['bank_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Account Name') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['account_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Account Number') }}</span>
                        <span class="text-[18px] font-extrabold text-brand bg-accent/20 px-3 py-1 select-all">{{ $account['account_number'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Branch') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['branch'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Swift Code') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['swift_code'] }}</span>
                    </div>
                </div>

            @elseif($method === 'telebirr')
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-xl bg-[#ff6b00] flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('Telebirr Mobile Payment') }}</h2>
                        <p class="text-[13px] text-gray-500">{{ __('Dial the USSD code or use the Telebirr app') }}</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Service') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['service_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Merchant Name') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['merchant_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Phone Number') }}</span>
                        <span class="text-[18px] font-extrabold text-brand bg-accent/20 px-3 py-1 select-all">{{ $account['phone_number'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-gray-400">{{ __('Merchant Code') }}</span>
                        <span class="text-[14px] font-semibold">{{ $account['merchant_code'] }}</span>
                    </div>
                </div>

                <!-- Telebirr Steps -->
                <div class="mt-8 p-6 bg-surface-muted">
                    <h3 class="text-[12px] font-bold uppercase tracking-[0.12em] mb-4">{{ __('How to Pay via Telebirr') }}</h3>
                    <ol class="space-y-3 text-[13px] text-gray-600">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-bold shrink-0">1</span>
                            <span>{{ __('Open your Telebirr app or dial') }} <strong><a href="tel:*127%23" class="text-brand underline">*127#</a></strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-bold shrink-0">2</span>
                            <span>{{ __('Select') }} <strong>{{ __('Send Money') }}</strong> {{ __('or') }} <strong>{{ __('Pay Merchant') }}</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-bold shrink-0">3</span>
                            <span>{{ __('Enter merchant number:') }} <strong class="select-all">{{ $account['phone_number'] }}</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-bold shrink-0">4</span>
                            <span>{{ __('Enter amount:') }} <strong>ETB {{ number_format($order->total, 2) }}</strong></span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-bold shrink-0">5</span>
                            <span>{{ __('Enter your Telebirr PIN to confirm') }}</span>
                        </li>
                    </ol>
                </div>

                <!-- USSD Quick Dial -->
                <div class="mt-6 p-5 bg-brand text-white rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-[14px]">{{ __('Quick Pay via USSD') }}</p>
                        <p class="text-white/60 text-[12px]">{{ __('Tap to dial directly from your phone') }}</p>
                    </div>
                    <a href="tel:*127%23" class="bg-white text-brand px-5 py-3 rounded-lg text-[12px] font-bold uppercase tracking-wider shrink-0 active:scale-95 transition-transform">
                        {{ __('Dial *127#') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Important Note -->
        <div class="bg-yellow-50 border border-yellow-200 p-5 mb-8 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div>
                <p class="text-[13px] font-semibold text-yellow-800 mb-1">{{ __('Important') }}</p>
                <p class="text-[12px] text-yellow-700 leading-relaxed">{{ __('Please include your order number') }} <strong>{{ $order->order_number }}</strong> {{ __('as the payment reference/note when making the transfer. This helps us verify your payment quickly.') }}</p>
            </div>
        </div>

        <!-- Amount Summary -->
        <div class="bg-surface-muted p-6 mb-8">
            <div class="flex justify-between items-center">
                <span class="text-[13px] font-semibold">{{ __('Total Amount to Pay') }}</span>
                <span class="text-2xl font-extrabold">ETB {{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Confirmation Form -->
        <div class="border border-gray-100 p-8">
            <h2 class="text-lg font-bold mb-2">{{ __('Confirm Your Payment') }}</h2>
            <p class="text-[13px] text-gray-500 mb-6">{{ __('After making the payment, fill in the details below so we can verify your order.') }}</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 mb-6">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-[12px]">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('payment.submit', $order->order_number) }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="{{ $method }}">
                <div class="space-y-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">
                            {{ $method === 'cbe' ? __('Transaction Reference / Receipt Number') : __('Telebirr Transaction ID') }} *
                        </label>
                        <input type="text" name="transaction_ref" value="{{ old('transaction_ref') }}" required
                            placeholder="{{ $method === 'cbe' ? __('e.g. CBE-20260718-XXXXX or receipt number') : __('e.g. TXN123456789') }}"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">{{ __('Your Full Name') }} *</label>
                            <input type="text" name="sender_name" value="{{ old('sender_name') }}" required
                                class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">{{ __('Your Phone Number') }} *</label>
                            <input type="text" name="sender_phone" value="{{ old('sender_phone', $order->customer_phone) }}" required
                                placeholder="+251..."
                                class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full mt-8 btn-primary py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                    {{ __('Submit Payment Confirmation') }}
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function() {
    var ua = navigator.userAgent || '';
    var isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(ua);
    var isAndroid = /Android/i.test(ua);
    var isIOS = /iPhone|iPad|iPod/i.test(ua);

    if (!isMobile) return;

    var banner = document.getElementById('mobileAppBanner');
    if (banner) {
        banner.classList.remove('hidden');
        banner.classList.add('block');
    }

    var method = '{{ $method }}';
    var phone = '{{ $account["phone_number"] ?? "" }}';
    var amount = '{{ $order->total }}';
    var orderNum = '{{ $order->order_number }}';

    if (method === 'telebirr') {
        autoLaunch('telebirr://', phone, amount, orderNum, 'telebirrFallback');
    } else if (method === 'cbe') {
        autoLaunch('cbeebirr://', phone, amount, orderNum, 'cbeFallback');
    }

    function autoLaunch(deepLink, phone, amount, orderNum, fallbackId) {
        var launchUrl = deepLink;
        var fallback = document.getElementById(fallbackId);

        if (isAndroid) {
            launchUrl = 'intent://pay?phone=' + encodeURIComponent(phone) +
                        '&amount=' + encodeURIComponent(amount) +
                        '&ref=' + encodeURIComponent(orderNum) +
                        '#Intent;scheme=' + deepLink.replace('://', '') +
                        ';package=' + (method === 'telebirr' ? 'com.telebirr' : 'com.cbe.mobilebanking') +
                        ';S.browser_fallback_url=' + encodeURIComponent('https://play.google.com/store/apps/details?id=' + (method === 'telebirr' ? 'com.telebirr' : 'com.cbe.mobilebanking')) +
                        ';end';
        } else if (isIOS) {
            launchUrl = deepLink;
        }

        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = launchUrl;
        document.body.appendChild(iframe);

        var opened = false;
        var timeout = setTimeout(function() {
            if (!opened && fallback) {
                fallback.classList.remove('hidden');
                fallback.classList.add('block');
            }
        }, 2500);

        window.addEventListener('pagehide', function() {
            opened = true;
            clearTimeout(timeout);
        });

        window.addEventListener('blur', function() {
            opened = true;
            clearTimeout(timeout);
        });

        setTimeout(function() {
            document.body.removeChild(iframe);
        }, 5000);
    }
})();
</script>
@endpush
