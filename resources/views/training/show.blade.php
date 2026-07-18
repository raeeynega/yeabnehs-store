@extends('layouts.app')

@section('title', $program->title . ' - YeaBneh Training')

@section('content')

<section class="py-12">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <nav class="text-[11px] text-gray-400 mb-10 uppercase tracking-[0.15em] font-medium">
            <a href="{{ route('home') }}" class="hover:text-brand transition">Home</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <a href="{{ route('training.index') }}" class="hover:text-brand transition">Training</a>
            <span class="mx-2.5 text-gray-300">/</span>
            <span class="text-brand">{{ $program->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Image -->
            <div class="relative overflow-hidden aspect-[4/3]">
                <img src="{{ $program->coverImage() }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                <div class="absolute top-5 left-5 flex items-center gap-2 bg-brand/80 backdrop-blur-sm text-white px-4 py-2 rounded-full">
                    <span class="w-5 h-5">{!! $program->typeIcon() !!}</span>
                    <span class="text-[11px] font-bold uppercase tracking-wider">{{ $program->typeLabel() }}</span>
                </div>
            </div>

            <!-- Info -->
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-6">{{ $program->title }}</h1>

                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl font-extrabold">${{ number_format($program->price, 2) }}</span>
                    @if($program->hasDiscount())
                        <span class="text-lg text-gray-400 line-through">${{ number_format($program->compare_at_price, 2) }}</span>
                    @endif
                    @if($program->duration)
                        <span class="text-[12px] text-gray-400 ml-2">/ {{ $program->duration }}</span>
                    @endif
                </div>

                @if($program->description)
                    <div class="text-[15px] text-gray-600 leading-relaxed mb-8">
                        {!! $program->description !!}
                    </div>
                @endif

                @if($program->getFeaturesArray())
                    <div class="space-y-3 mb-8">
                        @foreach($program->getFeaturesArray() as $feature)
                            <div class="flex items-center gap-3 text-[14px]">
                                <svg class="w-4 h-4 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                {{ $feature }}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($program->long_description)
                    <div class="text-[14px] text-gray-500 leading-relaxed mb-8">
                        {!! $program->long_description !!}
                    </div>
                @endif

                <a href="{{ route('training.book', $program) }}" class="btn-primary inline-block px-10 py-4 text-[12px] font-bold uppercase tracking-[0.15em]">
                    Book This Program
                </a>
            </div>
        </div>

        @if($relatedPrograms->count())
            <div class="mt-24">
                <h2 class="text-2xl font-extrabold uppercase tracking-[-0.02em] mb-10">Other Programs</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPrograms as $rp)
                        <a href="{{ route('training.show', $rp) }}" class="group block border border-gray-100 hover:border-gray-200 transition-all hover:shadow-lg hover:shadow-black/5">
                            <div class="relative overflow-hidden aspect-[16/10]">
                                <img src="{{ $rp->coverImage() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="p-5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $rp->typeLabel() }}</span>
                                <h3 class="text-[15px] font-bold uppercase mt-1 mb-1">{{ $rp->title }}</h3>
                                <span class="text-[14px] font-extrabold">${{ number_format($rp->price, 2) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
