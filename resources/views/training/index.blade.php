@extends('layouts.app')

@section('title', 'ሰነፍ ስልጠና - YeaBneh Store')

@section('content')

<!-- Hero -->
<section class="hero-gradient py-20 lg:py-28">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10 text-center">
        <p class="text-accent text-[11px] font-bold uppercase tracking-[0.3em] mb-4">YeaBneh Training</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold uppercase tracking-[-0.02em] text-white mb-6">
            Train With The Best
        </h1>
        <p class="text-gray-400 text-lg max-w-xl mx-auto font-light">
            Personalized fitness coaching tailored to your goals. 1-on-1 sessions, group classes, and online programs available.
        </p>
    </div>
</section>

<!-- Training Types Overview -->
<section class="py-16 bg-surface">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-8">
                <div class="w-14 h-14 bg-brand text-white rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-[0.12em] mb-2">1-on-1 Training</h3>
                <p class="text-[13px] text-gray-500">Fully personalized sessions focused on your specific goals and skill level.</p>
            </div>
            <div class="p-8">
                <div class="w-14 h-14 bg-brand text-white rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-[0.12em] mb-2">Group Classes</h3>
                <p class="text-[13px] text-gray-500">Train with a community. High-energy group sessions for all levels.</p>
            </div>
            <div class="p-8">
                <div class="w-14 h-14 bg-brand text-white rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-[0.12em] mb-2">Online Coaching</h3>
                <p class="text-[13px] text-gray-500">Train from anywhere with custom programming and video feedback.</p>
            </div>
        </div>
    </div>
</section>

<!-- Programs -->
<section class="py-20">
    <div class="max-w-[1680px] mx-auto px-5 lg:px-10">
        @if($programs->count())
            <div class="mb-12">
                <h2 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-[-0.02em]">ተመዝግቦ ያለዎት ፕሮግራሞች</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($programs as $program)
                    <div class="group bg-white border border-gray-100 rounded-xl hover:border-gray-200 transition-all duration-300 hover:shadow-lg hover:shadow-black/5 flex flex-col">
                        <div class="relative overflow-hidden aspect-[16/10] rounded-t-xl">
                            <img src="{{ $program->coverImage() }}" alt="{{ $program->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            <div class="absolute top-4 left-4 flex items-center gap-2">
                                <span class="text-white/90">{!! $program->typeIcon() !!}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-white/90">{{ $program->typeLabel() }}</span>
                            </div>
                            @if($program->hasDiscount())
                                <div class="absolute top-4 right-4">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 bg-red-600 text-white">-{{ $program->discountPercent() }}%</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-lg font-bold uppercase tracking-[-0.01em] mb-2">{{ $program->title }}</h3>
                            <p class="text-[13px] text-gray-500 line-clamp-2 mb-4 flex-1">{{ $program->description }}</p>

                            @if($program->getFeaturesArray())
                                <div class="space-y-2 mb-6">
                                    @foreach(array_slice($program->getFeaturesArray(), 0, 4) as $feature)
                                        <div class="flex items-center gap-2 text-[12px] text-gray-500">
                                            <svg class="w-3.5 h-3.5 text-accent shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl font-extrabold">${{ number_format($program->price, 2) }}</span>
                                        @if($program->hasDiscount())
                                            <span class="text-sm text-gray-400 line-through">${{ number_format($program->compare_at_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($program->duration)
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $program->duration }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('training.show', $program) }}" class="btn-primary px-6 py-3 text-[11px] font-bold uppercase tracking-[0.12em]">
            አሁን ይያዙ
        </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-24">
                <p class="text-xl text-gray-400">ስልጠና ፕሮግራሞች በቅርቡ ይመጣሉ።</p>
            </div>
        @endif
    </div>
</section>

@endsection
