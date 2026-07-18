@extends('layouts.app')

@section('title', $page->title . ' - YeaBneh Store')

@section('content')

<section class="py-16">
    <div class="max-w-3xl mx-auto px-5 lg:px-9">
        <h1 class="text-3xl lg:text-4xl font-extrabold uppercase tracking-[-0.02em] mb-10">{{ $page->title }}</h1>
        @if($page->content)
            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                {!! $page->content !!}
            </div>
        @endif
    </div>
</section>

@endsection
