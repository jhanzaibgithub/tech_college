<section class="hero-banners" aria-label="College banners" aria-roledescription="carousel" data-hero-banners>
    <div class="hero-banner-stage">
        @forelse ($banners as $banner)
            <div class="hero-banner-slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $banners->count() }}" @if(!$loop->first) hidden @endif>
                <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title }}" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
            </div>
        @empty
            <div class="hero-banner-slide" role="group" aria-label="Tech College">
                <img src="{{ asset('data/hero-students-placeholder.png') }}" alt="Students at Tech College" fetchpriority="high">
            </div>
        @endforelse
        @if ($banners->count() > 1)
            <button class="hero-banner-arrow hero-banner-prev" type="button" aria-label="Previous banner" data-banner-prev><i data-lucide="arrow-left"></i></button>
            <button class="hero-banner-arrow hero-banner-next" type="button" aria-label="Next banner" data-banner-next><i data-lucide="arrow-right"></i></button>
        @endif
        <div class="hero-banner-overlay">
            <div class="hero-banner-actions">
                <a class="button" href="#courses">Explore Courses <i data-lucide="arrow-right"></i></a>
                <a class="button button-gold" href="#admissions">Admissions Open <i data-lucide="graduation-cap"></i></a>
            </div>
        </div>
    </div>
</section>
