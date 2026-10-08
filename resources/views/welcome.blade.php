@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/hero-banners.css') }}?v={{ filemtime(public_path('css/hero-banners.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/course-slider.css') }}?v={{ filemtime(public_path('css/course-slider.css')) }}">
@endpush

@section('content')
        @include('partials.hero-banners')

        @if (! empty($site['features']))
        <section class="feature-strip container" data-reveal data-stagger style="--cols: {{ count($site['features']) }}" aria-label="How we help you">@foreach ($site['features'] as $feature)<article><span class="feature-icon"><i data-lucide="{{ $feature['icon'] }}"></i></span><h3>{{ $feature['title'] }}</h3>@if ($feature['text'])<p>{{ $feature['text'] }}</p>@endif</article>@endforeach</section>
        @endif

        <section class="news-ticker-bar" aria-label="Latest news">
            <div class="news-ticker-label"><i data-lucide="megaphone"></i><span>LATEST NEWS</span></div>
            <div class="news-ticker-track-wrap" tabindex="0" aria-label="News announcements">
                <div class="news-ticker-track" id="newsTicker">
                    @for ($copy = 0; $copy < 2; $copy++)
                        <div class="news-ticker-group" @if($copy) aria-hidden="true" @endif>
                            @forelse ($tickerItems as $item)
                                <span class="news-ticker-item">{{ $item->title }}</span>
                            @empty
                                <span class="news-ticker-item">Explore our skills development programs &mdash; Contact our admissions team at {{ $site['phone'] }}</span>
                                <span class="news-ticker-item">Tech College of Skills Development &amp; Placement &mdash; Building skills, building futures</span>
                            @endforelse
                        </div>
                    @endfor
                </div>
            </div>
            <button class="ticker-toggle" type="button" aria-label="Pause news ticker" aria-pressed="false"><span class="ticker-pause-symbol" aria-hidden="true">&#10074;&#10074;</span></button>
            <a class="news-ticker-contact" href="{{ $site['phone_href'] }}"><i data-lucide="phone-call"></i><span>Registration: {{ $site['phone'] }}</span></a>
        </section>

        <section class="section courses-section" id="courses" aria-labelledby="popular-courses-title">
            <div class="container">
                <div class="home-courses-head" data-reveal>
                    <div class="course-list-heading">
                        <p class="section-kicker">OUR PROGRAMS</p>
                        <h2 id="popular-courses-title">Top Courses List</h2>
                    </div>
                </div>
                @if ($courses->isEmpty())
                    <p class="empty-panel-text">New courses are on the way. Contact our team for upcoming programs.</p>
                @else
                    <div class="course-grid-list" data-reveal data-stagger>
                        @foreach ($courses as $item)
                            @include('partials.course-card', ['item' => $item])
                        @endforeach
                    </div>
                    <div class="view-all-cta" data-reveal>
                        <span class="view-all-rule" aria-hidden="true"></span>
                        <a class="view-all-btn" href="{{ route('courses.index') }}">
                            <span class="view-all-text"><strong>View all courses</strong><small>Explore all {{ $courseTotal }} {{ \Illuminate\Support\Str::plural('program', $courseTotal) }}</small></span>
                            <span class="view-all-arrow" aria-hidden="true"><i data-lucide="arrow-right"></i></span>
                        </a>
                        <span class="view-all-rule" aria-hidden="true"></span>
                    </div>
                @endif
            </div>
        </section>

        @include('partials.journey-section')

        @include('partials.about-section')

        @include('partials.stats-section')

        {{-- News & Events Carousel --}}
        <section class="news-section-home" id="news-events" data-reveal>
            <div class="container">
                <div class="section-heading courses-heading" style="margin-bottom:36px;">
                    <span></span>
                    <div>
                        <h2>Latest News & Events</h2>
                        <p>Stay updated with what's happening at Tech College</p>
                    </div>
                    <span></span>
                </div>
                <div class="news-cards-grid news-carousel owl-carousel owl-theme">
                    @foreach ($newsEvents as $newsEvent)
                        <article class="news-card-v2">
                            <div class="news-card-v2-img">
                                <img src="{{ \App\Support\Media::url($newsEvent->image_path) }}" alt="{{ $newsEvent->title }}" loading="lazy">
                            </div>
                            <div class="news-card-v2-body">
                                @if($newsEvent->event_date)
                                    <span class="news-card-v2-date"><i data-lucide="calendar-days"></i> {{ $newsEvent->event_date?->format('M d, Y') }}</span>
                                @endif
                                <h3>{{ $newsEvent->title }}</h3>
                                <p>{{ $newsEvent->summary }}</p>
                            </div>
                        </article>
                    @endforeach
                    @if($newsEvents->isEmpty())
                        <p class="empty-panel-text">No news or events at this time. Check back soon!</p>
                    @endif
                </div>
            </div>
        </section>

        {{-- Student stories (no longer in the navbar; still reachable at /#placement) --}}
        <section class="testimonials-cards-section" id="placement" data-reveal>
            <div class="container">
                <div class="section-heading courses-heading">
                    <span></span>
                    <div>
                        <p class="section-kicker">THEIR JOURNEY. YOUR INSPIRATION.</p><h2>What our students say</h2>
                        <p>Real stories from our graduates who transformed their careers</p>
                    </div>
                    <span></span>
                </div>
                <div class="testimonials-cards-grid tcard-carousel owl-carousel owl-theme">
                    @forelse ($testimonials as $testimonial)
                        <article class="tcard">
                            <div class="tcard-photo-wrap">
                                <img src="{{ \App\Support\Media::url($testimonial->image_path, \App\Support\Media::AVATAR_FALLBACK) }}" alt="{{ $testimonial->student_name }}" loading="lazy">
                            </div>
                            <div class="tcard-stars" aria-hidden="true">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
                            <h3 class="tcard-name">{{ $testimonial->student_name }}</h3>
                            <span class="tcard-role">{{ $testimonial->designation ?: 'Student' }}</span>
                            <p class="tcard-text">{{ $testimonial->message }}</p>
                            @if (mb_strlen($testimonial->message) > 240)
                                <button class="testimonial-more" type="button" aria-expanded="false">Read full story <span aria-hidden="true">+</span></button>
                            @endif
                        </article>
                    @empty
                        <p class="empty-panel-text">Student stories will be shared here soon.</p>
                    @endforelse
                </div>
            </div>
        </section>

        @include('partials.faq-section')

        <section class="cta container" id="admissions" data-reveal><div><i data-lucide="graduation-cap"></i><div><h2>Start your skill journey today</h2><p>Join Tech College and build a brighter tomorrow.</p></div></div><button class="button button-gold" type="button" data-open-enrollment>Enrolled now <i data-lucide="arrow-right"></i></button></section>
@endsection

@push('vendor-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/home.js') }}?v={{ filemtime(public_path('js/home.js')) }}"></script>
    <script src="{{ asset('js/hero-banners.js') }}?v={{ filemtime(public_path('js/hero-banners.js')) }}"></script>
@endpush
