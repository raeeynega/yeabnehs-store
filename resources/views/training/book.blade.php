@extends('layouts.app')

@section('title', 'Book: ' . $program->title . ' - YeaBneh Training')

@section('content')

<section class="py-16">
    <div class="max-w-2xl mx-auto px-5">
        <nav class="text-[11px] text-gray-400 mb-10 uppercase tracking-[0.15em] font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand transition">Home</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('training.index') }}" class="hover:text-brand transition">Training</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('training.show', $program) }}" class="hover:text-brand transition">{{ $program->title }}</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <span class="text-brand">Book</span>
        </nav>

        <div class="bg-surface-muted p-8 mb-8 flex items-center gap-5">
            <div class="w-16 h-16 bg-brand text-white rounded-full flex items-center justify-center shrink-0">
                <span class="w-7 h-7">{!! $program->typeIcon() !!}</span>
            </div>
            <div>
                <p class="text-[11px] text-gray-400 uppercase tracking-wider">{{ $program->typeLabel() }}</p>
                <h2 class="text-xl font-bold uppercase">{{ $program->title }}</h2>
                <p class="text-[14px] font-extrabold mt-1">${{ number_format($program->price, 2) }} @if($program->duration) <span class="text-gray-400 font-normal text-[12px]">/ {{ $program->duration }}</span> @endif</p>
            </div>
        </div>

        <form action="{{ route('training.book.submit', $program) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Full Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                    @error('customer_name') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        @error('customer_email') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Phone</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Preferred Date *</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" required
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                        @error('preferred_date') <p class="text-red-500 text-[12px] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Preferred Time *</label>
                        <select name="preferred_time" required class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors">
                            <option value="">Select time</option>
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
                    <label class="block text-[11px] font-bold uppercase tracking-[0.15em] text-gray-500 mb-2">Message (optional)</label>
                    <textarea name="message" rows="3"
                        class="w-full border border-gray-200 px-4 py-3.5 text-[14px] focus:outline-none focus:border-brand transition-colors placeholder:text-gray-400"
                        placeholder="Tell us about your goals, experience level, or any questions...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="w-full btn-primary py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                    Submit Booking Request
                </button>
                <p class="text-center text-[12px] text-gray-400">We'll confirm your booking within 24 hours via email.</p>
            </div>
        </form>
    </div>
</section>

@endsection
