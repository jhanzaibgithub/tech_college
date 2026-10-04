@php($hero = $site['pages'][$page])
<section class="page-hero" style="--hero-image: url('{{ $hero['image'] }}')">
    <div class="page-hero-bg" aria-hidden="true"></div>
    <div class="container page-hero-inner">
        <p class="section-kicker">{{ $kicker }}</p>
        <h1>{{ $hero['title'] }}</h1>
        @if ($hero['text'])<p class="page-hero-text">{{ $hero['text'] }}</p>@endif
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span aria-current="page">{{ $kicker }}</span></nav>
    </div>
</section>
