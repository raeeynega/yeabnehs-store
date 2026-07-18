@extends('layouts.app')

@section('title', 'Checkout - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-10">Checkout</h1>

        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact -->
                    <div class="border border-gray-100 p-7 rounded-xl">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Full Name *</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $user->name ?? '') }}" required
                                    class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors rounded-lg">
                                @error('customer_name') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Email *</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email', $user->email ?? '') }}" required
                                    class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors rounded-lg">
                                @error('customer_email') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Phone</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone ?? '') }}"
                                    class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div class="border border-gray-100 p-7 rounded-xl">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Shipping Address</h2>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Address *</label>
                        <textarea name="shipping_address" rows="3" required
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors rounded-lg">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Notes -->
                    <div class="border border-gray-100 p-7 rounded-xl">
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Order Notes (optional)</label>
                        <textarea name="notes" rows="2"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors rounded-lg placeholder:text-gray-400" placeholder="Any special requests...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Payment Info -->
                    <div class="border border-gray-100 p-7 rounded-xl">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-4">Payment</h2>
                        <div class="bg-surface-muted p-5 rounded-lg flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-accent-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-semibold mb-1">Pay with CBE or Telebirr</p>
                                <p class="text-[12px] text-gray-500 leading-relaxed">After placing your order, you'll be redirected to choose between <strong>Commercial Bank of Ethiopia (CBE)</strong> bank transfer or <strong>Telebirr</strong> mobile payment. You'll receive full payment instructions.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-surface-muted p-7 rounded-xl sticky top-24">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Your Order</h2>
                        <div class="space-y-4 mb-6">
                            @foreach($products as $slug => $item)
                                <div class="flex gap-3 items-center">
                                    <div class="w-14 h-14 bg-white shrink-0 overflow-hidden rounded-lg border border-gray-100">
                                        <img src="{{ $item['product']->primaryImage() }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[12px] font-medium line-clamp-1">{{ $item['product']->name }}</p>
                                        <p class="text-[11px] text-gray-400">x{{ $item['qty'] }}</p>
                                    </div>
                                    <p class="text-[12px] font-bold shrink-0">${{ number_format($item['product']->price * $item['qty'], 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="h-px bg-gray-200"></div>
                        <div class="space-y-2.5 text-[14px] mt-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-bold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Shipping</span>
                                <span class="font-bold">{{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="h-px bg-gray-200 my-2"></div>
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total</span>
                                <span class="font-extrabold">${{ number_format($subtotal + $shipping, 2) }}</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 btn-primary py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection
