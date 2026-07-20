@extends('layouts.app')

@section('title', 'Book: ' . $program->title . ' - YeaBneh Training')

@section('content')

<section class="py-16">
    <div class="max-w-2xl mx-auto px-5">
        <nav class="text-[11px] text-gray-400 mb-10 uppercase tracking-[0.15em] font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand transition">ዋና ገጽ</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('training.index') }}" class="hover:text-brand transition">ስልጠና</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('training.show', $program) }}" class="hover:text-brand transition">{{ $program->title }}</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <span class="text-brand">ቦታ ማስያዣ</span>
        </nav>

        <div class="bg-surface-muted p-8 mb-8 flex items-center gap-5">
            <div class="w-16 h-16 bg-brand text-white rounded-full flex items-center justify-center shrink-0">
                <span class="w-7 h-7">{!! $program->typeIcon() !!}</span>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 uppercase tracking-wider">{{ $program->typeLabel() }}</p>
                <h2 class="text-xl font-bold uppercase">{{ $program->title }}</h2>
                <p class="text-[14px] font-extrabold mt-1" id="totalPrice">${{ number_format($program->price, 2) }}</p>
            </div>
        </div>

        <form action="{{ route('training.book.submit', $program) }}" method="POST">
            @csrf
            <div class="space-y-6">

                <!-- Frequency Selection -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-3">ስልጠና ድግරታ *</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($program->getPricingTiers() as $tier)
                            <label class="frequency-option group relative border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-gray-400 transition-all text-center
                                        {{ (request('sessions', 1) == $tier['sessions']) ? 'border-brand shadow-md' : '' }}"
                                   data-sessions="{{ $tier['sessions'] }}" data-price="{{ $tier['price'] }}">
                                @if(isset($tier['save']))
                                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-brand text-[9px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">Save {{ $tier['save'] }}%</span>
                                @endif
                                <input type="radio" name="sessions_per_week" value="{{ $tier['sessions'] }}"
                                       class="hidden" {{ request('sessions', 1) == $tier['sessions'] ? 'checked' : '' }}>
                                <p class="text-[13px] font-bold uppercase mb-1">{{ $tier['label'] }}</p>
                                <p class="text-xl font-extrabold">${{ number_format($tier['price'], 2) }}</p>
                                <p class="text-[11px] text-gray-400 mt-1">/ session</p>
                            </label>
                        @endforeach
                    </div>
                    <input type="hidden" name="frequency" id="frequencyInput" value="{{ request('sessions', 1) . 'x per week' }}">
                    @error('sessions_per_week') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ሙሉ ስም *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required
                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                    @error('customer_name') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ኢሜይል *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        @error('customer_email') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">ስልክ</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">የመጀመሪያ ቀን *</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" required
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        @error('preferred_date') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">የተመረጠ ሰዓት *</label>
                        <select name="preferred_time" required class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                            <option value="">ሰዓት ይምረጡ</option>
                            <option value="06:00 AM" {{ old('preferred_time') == '06:00 AM' ? 'selected' : '' }}>6:00 AM</option>
                            <option value="07:00 AM" {{ old('preferred_time') == '07:00 AM' ? 'selected' : '' }}>7:00 AM</option>
                            <option value="08:00 AM" {{ old('preferred_time') == '08:00 AM' ? 'selected' : '' }}>8:00 AM</option>
                            <option value="09:00 AM" {{ old('preferred_time') == '09:00 AM' ? 'selected' : '' }}>9:00 AM</option>
                            <option value="10:00 AM" {{ old('preferred_time') == '10:00 AM' ? 'selected' : '' }}>10:00 AM</option>
                            <option value="11:00 AM" {{ old('preferred_time') == '11:00 AM' ? 'selected' : '' }}>11:00 AM</option>
                            <option value="12:00 PM" {{ old('preferred_time') == '12:00 PM' ? 'selected' : '' }}>12:00 PM</option>
                            <option value="01:00 PM" {{ old('preferred_time') == '01:00 PM' ? 'selected' : '' }}>1:00 PM</option>
                            <option value="02:00 PM" {{ old('preferred_time') == '02:00 PM' ? 'selected' : '' }}>2:00 PM</option>
                            <option value="03:00 PM" {{ old('preferred_time') == '03:00 PM' ? 'selected' : '' }}>3:00 PM</option>
                            <option value="04:00 PM" {{ old('preferred_time') == '04:00 PM' ? 'selected' : '' }}>4:00 PM</option>
                            <option value="05:00 PM" {{ old('preferred_time') == '05:00 PM' ? 'selected' : '' }}>5:00 PM</option>
                            <option value="06:00 PM" {{ old('preferred_time') == '06:00 PM' ? 'selected' : '' }}>6:00 PM</option>
                        </select>
                        @error('preferred_time') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">መልዕክት (አማራጭ)</label>
                    <textarea name="message" rows="3"
                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors placeholder:text-gray-400"
                        placeholder="ስለ ተGGLEዎችዎ, የተሞከረ ደረጃ, ወይም ማንኛውም ጥያቄዎች ይንግሩን...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="w-full btn-primary py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                    የቦታ ማስያዣ ጥያቄ ያስፈልጉ
                </button>
                <p class="text-center text-[12px] text-gray-400">በ24 ሰዓት ውስጥ በኢሜይል የቦታ ማስያዣዎን እናረጋግጣለን።</p>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const options = document.querySelectorAll('.frequency-option');
    const frequencyInput = document.getElementById('frequencyInput');
    const totalPrice = document.getElementById('totalPrice');
    const sessionsLabels = { 1: '1x per week', 2: '2x per week', 3: '3x per week', 5: '5x per week' };

    options.forEach(option => {
        option.addEventListener('click', function() {
            options.forEach(o => o.classList.remove('border-brand', 'shadow-md'));
            this.classList.add('border-brand', 'shadow-md');
            this.querySelector('input[type="radio"]').checked = true;
            const sessions = this.dataset.sessions;
            const price = this.dataset.price;
            frequencyInput.value = sessionsLabels[sessions] || sessions + 'x per week';
            totalPrice.textContent = '$' + parseFloat(price).toFixed(2);
        });
    });
});
</script>
@endpush

@endsection
