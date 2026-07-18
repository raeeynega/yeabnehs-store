@extends('layouts.app')

@section('title', 'Courses - YeaBneh Store')

@section('content')

<!-- Hero -->
<section class="hero-gradient py-20 lg:py-28">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10 text-center">
        <p class="text-accent text-[11px] font-bold uppercase tracking-[0.3em] mb-4">YeaBneh Academy</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold uppercase tracking-[-0.02em] text-white mb-6">
            Master Calisthenics
        </h1>
        <p class="text-gray-400 text-lg max-w-xl mx-auto font-light">
            Structured courses designed to take you from beginner to advanced. Learn proper form, build strength, and unlock skills.
        </p>
    </div>
</section>

<!-- Course Grid -->
<section class="py-20">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        @if($courses->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="group block bg-white border border-gray-100 rounded-xl hover:border-gray-200 transition-all duration-300 hover:shadow-lg hover:shadow-black/5">
                        <div class="relative overflow-hidden aspect-[16/10] rounded-t-xl">
                            <img src="{{ $course->coverImage() }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="text-[10px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 {{ $course->levelColor() }}">{{ $course->level }}</span>
                            </div>
                            @if($course->hasDiscount())
                                <div class="absolute top-4 right-4">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 bg-red-600 text-white">-{{ $course->discountPercent() }}%</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold uppercase tracking-[-0.01em] mb-2 group-hover:opacity-60 transition">{{ $course->title }}</h3>
                            <p class="text-[13px] text-gray-500 line-clamp-2 mb-4">{{ $course->description }}</p>
                            <div class="flex items-center gap-4 text-[12px] text-gray-400 mb-5">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $course->duration_weeks }} weeks
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                    {{ $course->lessons_count }} lessons
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-extrabold">${{ number_format($course->price, 2) }}</span>
                                @if($course->hasDiscount())
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($course->compare_at_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <p class="text-xl text-gray-400">Courses coming soon.</p>
            </div>
        @endif
    </div>
</section>

@endsection
