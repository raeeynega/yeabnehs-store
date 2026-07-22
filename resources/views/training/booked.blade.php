@extends('layouts.app')

@section('title', __('Booking Submitted') . ' - ' . __('YeaBneh Training'))

@section('content')

<section class="py-16">
    <div class="max-w-xl mx-auto px-5 text-center">
        <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold uppercase tracking-[-0.02em] mb-4">{{ __('Booking Submitted!') }}</h1>
        <p class="text-gray-500 mb-2 text-[15px]">{{ __('Thank you for your booking request.') }}</p>
        <p class="text-[13px] text-gray-400 mb-10">{{ __('We\'ll review your request and confirm your booking via email within 24 hours.') }}</p>

        <div class="bg-surface-muted p-7 text-left mb-10">
            <p class="text-[13px] text-gray-500 text-center">{{ __('If you have questions, reach out to our support team.') }}</p>
        </div>

        <a href="{{ route('training.index') }}" class="btn-primary inline-block px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
            {{ __('View More Programs') }}
        </a>
    </div>
</section>

@endsection
