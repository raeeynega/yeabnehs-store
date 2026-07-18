@extends('layouts.app')

@section('title', 'My Account - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-[1100px] mx-auto px-5">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em]">My Account</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[12px] font-semibold uppercase tracking-[0.12em] text-gray-400 hover:text-red-600 transition-colors">
                    Sign Out
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 text-[13px] mb-8">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 text-[13px] mb-8">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="border border-gray-100 p-7 text-center mb-6">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full mx-auto mb-4 object-cover border-2 border-gray-100">
                    @else
                        <div class="w-20 h-20 rounded-full bg-brand text-white flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            {{ $user->getInitials() }}
                        </div>
                    @endif
                    <h2 class="text-lg font-bold">{{ $user->name }}</h2>
                    <p class="text-[13px] text-gray-500">{{ $user->email }}</p>
                    @if($user->provider)
                        <span class="inline-block mt-2 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-accent/20 text-brand rounded-full">
                            {{ ucfirst($user->provider) }}
                        </span>
                    @endif
                </div>

                <nav class="space-y-1">
                    <a href="#profile" class="block py-3 px-5 text-[13px] font-semibold bg-surface-muted hover:bg-gray-100 transition-colors">Profile</a>
                    <a href="#orders" class="block py-3 px-5 text-[13px] font-semibold hover:bg-surface-muted transition-colors">Orders</a>
                    @if(!$user->hasPassword())
                        <a href="#set-password" class="block py-3 px-5 text-[13px] font-semibold hover:bg-surface-muted transition-colors">Set Password</a>
                    @endif
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Profile -->
                <div id="profile" class="border border-gray-100 p-7">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Profile Information</h3>
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Phone</label>
                                    <input type="text" name="phone" value="{{ $user->phone }}"
                                        placeholder="+251..."
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full border border-gray-100 bg-surface-muted px-4 py-3.5 text-[14px] text-gray-400 cursor-not-allowed">
                                <p class="text-[11px] text-gray-400 mt-1.5">Email cannot be changed</p>
                            </div>
                        </div>
                        <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                            Update Profile
                        </button>
                    </form>
                </div>

                <!-- Password -->
                @if($user->hasPassword())
                    <div class="border border-gray-100 p-7">
                        <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Change Password</h3>
                        <form method="POST" action="{{ route('account.password') }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Current Password</label>
                                    <input type="password" name="current_password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                    @error('current_password') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">New Password</label>
                                    <input type="password" name="password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                    @error('password') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                Update Password
                            </button>
                        </form>
                    </div>
                @else
                    <div id="set-password" class="border border-gray-100 p-7">
                        <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-2">Set a Password</h3>
                        <p class="text-[13px] text-gray-500 mb-6">You signed up via {{ ucfirst($user->provider) }}. Set a password so you can also login with email.</p>
                        <form method="POST" action="{{ route('account.set-password') }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">New Password</label>
                                    <input type="password" name="password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                Set Password
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Orders -->
                <div id="orders" class="border border-gray-100 p-7">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">Recent Orders</h3>
                    @if($orders->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-[14px] text-gray-400 mb-4">No orders yet</p>
                            <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-100 p-5 hover:border-gray-200 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-[14px] font-bold">{{ $order->order_number }}</span>
                                            <span class="text-[12px] text-gray-400 ml-3">{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full
                                            @if($order->status === 'delivered') bg-green-100 text-green-700
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[13px] text-gray-500">{{ $order->items->count() }} item(s)</span>
                                        <span class="text-[14px] font-bold">ETB {{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <a href="{{ route('payment.pending', $order->order_number) }}" class="block mt-3 text-[12px] font-semibold text-brand hover:underline">
                                        View Order &rarr;
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
