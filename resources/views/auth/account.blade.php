@extends('layouts.app')

@section('title', 'My Account - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-[1100px] mx-auto px-5">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em]">የእኔ መለያ</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[12px] font-semibold uppercase tracking-[0.12em] text-gray-400 hover:text-red-600 transition-colors">
                    ይውጡ
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
                    <a href="#profile" class="block py-3 px-5 text-[13px] font-semibold bg-surface-muted hover:bg-gray-100 transition-colors">ፕሮፋይል</a>
                    <a href="#orders" class="block py-3 px-5 text-[13px] font-semibold hover:bg-surface-muted transition-colors">ትዕዛዞች</a>
                    @if(!$user->hasPassword())
                        <a href="#set-password" class="block py-3 px-5 text-[13px] font-semibold hover:bg-surface-muted transition-colors">የይለፍ ቃል ያስቀምጡ</a>
                    @endif
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Profile -->
                <div id="profile" class="border border-gray-100 p-7">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">የፕሮፋይል መረጃ</h3>
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ስም</label>
                                    <input type="text" name="name" value="{{ $user->name }}" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ስልክ</label>
                                    <input type="text" name="phone" value="{{ $user->phone }}"
                                        placeholder="+251..."
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ኢሜይል</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full border border-gray-100 bg-surface-muted px-4 py-3.5 text-[14px] text-gray-400 cursor-not-allowed">
                                <p class="text-[11px] text-gray-400 mt-1.5">ኢሜይል መቀየር አይችልም</p>
                            </div>
                        </div>
                        <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
ፕሮፋይል ያዘምኑ
                        </button>
                    </form>
                </div>
            </div>

            <!-- Password -->
                @if($user->hasPassword())
                    <div class="border border-gray-100 p-7">
                        <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">የይለፍ ቃል ቀይር</h3>
                        <form method="POST" action="{{ route('account.password') }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">የአሁኑ የይለፍ ቃል</label>
                                    <input type="password" name="current_password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                    @error('current_password') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">አዲስ የይለፍ ቃል</label>
                                    <input type="password" name="password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                    @error('password') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">የይለፍ ቃል ያረጋግጡ</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                የይለፍ ቃል ያዘምኑ
                            </button>
                        </form>
                    </div>
                @else
                    <div id="set-password" class="border border-gray-100 p-7">
                        <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-2">የይለፍ ቃል ያስቀምጡ</h3>
                        <p class="text-[13px] text-gray-500 mb-6">በ {{ ucfirst($user->provider) }} ተመዝገበዋል። የይለፍ ቃል ያስቀምጡ በኢሜይልም ሊያስገቡ ይችላሉ።</p>
                        <form method="POST" action="{{ route('account.set-password') }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">አዲስ የይለፍ ቃል</label>
                                    <input type="password" name="password" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">የይለፍ ቃል ያረጋግጡ</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                                </div>
                            </div>
                            <button type="submit" class="mt-6 btn-primary px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                የይለፍ ቃል ያስቀምጡ
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Orders -->
                <div id="orders" class="border border-gray-100 p-7">
                    <h3 class="text-[13px] font-bold uppercase tracking-[0.15em] mb-6">የቅርብ ትዕዛዞች</h3>
                    @if($orders->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-[14px] text-gray-400 mb-4">ሁለතኔ ትዕዛዞች የሉም</p>
                            <a href="{{ route('shop.index') }}" class="btn-primary inline-block px-8 py-3 text-[12px] font-bold uppercase tracking-[0.15em]">
                                ግዢ ይጀምሩ
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
                                         ትዕዛዙን ይመልከቱ &rarr;
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
