@extends('layouts.public')

@section('title', 'About Us | Tech College')
@section('description', \Illuminate\Support\Str::limit($site['about']['description'], 155))

@section('content')
    @include('partials.page-hero', ['page' => 'about', 'kicker' => 'About Us'])
    <section class="section about-page">
        <div class="container about-page-grid" data-reveal>
            <div class="about-page-image"><img src="{{ $site['about']['image'] }}" alt="Tech College campus"></div>
            <div class="about-page-copy">
                <h2 class="about-heading">{{ $site['about']['title'] }}</h2>
                @if ($site['about']['tagline'])<p class="about-tagline">{{ $site['about']['tagline'] }}</p>@endif
                <p class="about-description">{!! nl2br(e($site['about']['description'])) !!}</p>
                @if ($site['about']['institution'])
                    <p>{!! nl2br(e($site['about']['institution'])) !!}</p>
                @endif
                <ul class="about-points">
                    @foreach ($site['about']['points'] as $point)
                        <li><i data-lucide="circle-check"></i> {{ $point }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if ($site['about']['mission'] || $site['about']['vision'])
            <div class="container mv-grid" data-reveal data-stagger>
                @if ($site['about']['mission'])
                    <article class="mv-card"><span class="mv-icon"><i data-lucide="target"></i></span><h2>Our Mission</h2><p>{{ $site['about']['mission'] }}</p></article>
                @endif
                @if ($site['about']['vision'])
                    <article class="mv-card"><span class="mv-icon"><i data-lucide="eye"></i></span><h2>Our Vision</h2><p>{{ $site['about']['vision'] }}</p></article>
                @endif
            </div>
        @endif
    </section>
    @include('partials.stats-section')
@endsection
