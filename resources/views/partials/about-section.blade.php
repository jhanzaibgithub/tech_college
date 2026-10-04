<section class="section about" id="about" data-reveal>
    <div class="container about-grid">
        <div class="campus-art"><img src="{{ $site['about']['image'] }}" alt="Tech College campus" loading="lazy"></div>
        <div class="about-copy">
            <h2>{{ $site['about']['title'] }}</h2>
            @if ($site['about']['tagline'])<p class="lead">{{ $site['about']['tagline'] }}</p>@endif
            <ul>
                @foreach ($site['about']['points'] as $point)
                    <li><i data-lucide="circle-check"></i> {{ $point }}</li>
                @endforeach
            </ul>
            <a class="text-link about-more" href="{{ route('about') }}">Read more about us <i data-lucide="arrow-right"></i></a>
        </div>
        @if ($site['about']['quote'])
            <blockquote><i data-lucide="quote"></i><p>{{ $site['about']['quote'] }}</p><cite>Tech College</cite></blockquote>
        @endif
    </div>
</section>
