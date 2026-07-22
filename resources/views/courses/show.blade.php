@extends('layouts.app')

@section('title', $course->title . ' - YeaBneh Courses')

@section('content')

<section class="py-12">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <nav class="text-[11px] text-gray-400 mb-10 uppercase tracking-[0.15em] font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand transition">{{ __('Home') }}</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('courses.index') }}" class="hover:text-brand transition">{{ __('Courses') }}</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <span class="text-brand">{{ $course->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main -->
            <div class="lg:col-span-2">
                <div class="relative overflow-hidden aspect-video mb-8">
                    <img src="{{ $course->coverImage() }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                        <div class="w-20 h-20 bg-white/90 rounded-full flex items-center justify-center cursor-pointer hover:bg-accent transition-colors duration-300">
                            <svg class="w-8 h-8 text-brand ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="text-[10px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 {{ $course->levelColor() }}">{{ $course->level }}</span>
                    <span class="text-[12px] text-gray-400">{{ $course->duration_weeks }} {{ __('weeks') }}</span>
                    <span class="text-[12px] text-gray-400">{{ $course->lessons_count }} {{ __('lessons') }}</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-6">{{ $course->title }}</h1>

                @if($course->long_description)
                    <div class="text-[15px] text-gray-600 leading-relaxed mb-10">
                        {!! $course->long_description !!}
                    </div>
                @elseif($course->description)
                    <div class="text-[15px] text-gray-600 leading-relaxed mb-10">
                        {!! $course->description !!}
                    </div>
                @endif

                <!-- Curriculum -->
                @if($course->lessons->count())
                    <div class="mt-10">
                        <h2 class="text-xl font-bold uppercase tracking-[-0.01em] mb-6">{{ __('Curriculum') }}</h2>
                        <div class="space-y-2">
                            @foreach($course->lessons as $lesson)
                                <div class="flex items-center gap-4 p-4 border border-gray-100 hover:border-gray-200 transition-colors">
                                    <div class="w-10 h-10 bg-surface-muted rounded-full flex items-center justify-center shrink-0">
                                        <span class="text-[11px] font-bold text-gray-500">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[14px] font-semibold">{{ $lesson->title }}</p>
                                        @if($lesson->description)
                                            <p class="text-[12px] text-gray-400 mt-0.5">{{ $lesson->description }}</p>
                                        @endif
                                    </div>
                                    @if($lesson->duration_minutes)
                                        <span class="text-[12px] text-gray-400 shrink-0">{{ $lesson->duration_minutes }} min</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-surface-muted p-7 sticky top-24">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-3xl font-extrabold">${{ number_format($course->price, 2) }}</span>
                        @if($course->hasDiscount())
                            <span class="text-lg text-gray-400 line-through">${{ number_format($course->compare_at_price, 2) }}</span>
                        @endif
                    </div>
                    <button class="w-full btn-primary py-4 text-[12px] font-bold uppercase tracking-[0.15em] mb-4">
                        {{ __('Register Now') }}
                    </button>
                    <button class="w-full btn-outline py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                        {{ __('Add to Cart') }}
                    </button>

                    <div class="mt-8 space-y-3.5 text-[13px] text-gray-500">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $course->duration_weeks }} {{ __('weeks program') }}
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $course->lessons_count }} {{ __('video lessons') }}
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Lifetime access') }}
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Completion certificate') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedCourses->count())
            <div class="mt-24">
                <h2 class="text-2xl font-extrabold uppercase tracking-[-0.02em] mb-10">{{ __('More Courses') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedCourses as $rc)
                        <a href="{{ route('courses.show', $rc) }}" class="group block border border-gray-100 hover:border-gray-200 transition-all hover:shadow-lg hover:shadow-black/5">
                            <div class="relative overflow-hidden aspect-[16/10]">
                                <img src="{{ $rc->coverImage() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="p-5">
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $rc->levelColor() }} px-2 py-0.5">{{ $rc->level }}</span>
                                <h3 class="text-[15px] font-bold uppercase mt-2 mb-1">{{ $rc->title }}</h3>
                                <span class="text-[14px] font-extrabold">${{ number_format($rc->price, 2) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
