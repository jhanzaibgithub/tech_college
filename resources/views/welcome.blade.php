<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tech College of Skills Development and Placement helps students build job-ready skills.">
    <title>Tech College | Skills Development & Placement</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-banners.css') }}?v={{ filemtime(public_path('css/hero-banners.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/course-slider.css') }}">
</head>
<body>
    <div class="topbar"><div class="container topbar-inner"><span><i data-lucide="mail"></i> techcollegepak@gmail.com</span><span><i data-lucide="phone"></i> 051-4627600</span><span class="follow">Follow Us: <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a><a href="#" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a><a href="#" aria-label="YouTube"><i data-lucide="youtube"></i></a></span></div></div>
    @include('partials.site-header')
    <main id="home">
        @include('partials.hero-banners')

        <section class="feature-strip container">@foreach ($features as $feature)<article><span class="feature-icon"><i data-lucide="{{ $feature['icon'] }}"></i></span><h3>{{ $feature['title'] }}</h3><p>{{ $feature['text'] }}</p></article>@endforeach</section>

        <section class="news-ticker-bar" aria-label="Latest news">
            <div class="news-ticker-label"><i data-lucide="megaphone"></i><span>LATEST NEWS</span></div>
            <div class="news-ticker-track-wrap" tabindex="0" aria-label="News announcements">
                <div class="news-ticker-track" id="newsTicker">
                    @for ($copy = 0; $copy < 2; $copy++)
                        <div class="news-ticker-group" @if($copy) aria-hidden="true" @endif>
                            @forelse ($tickerItems as $item)
                                <span class="news-ticker-item">@if($item->event_date)<b>{{ $item->event_date->format('d M Y') }}:</b>@endif {{ $item->title }} &mdash; {{ $item->summary }}</span>
                            @empty
                                <span class="news-ticker-item">Explore our skills development programs &mdash; Contact our admissions team at 051-4627600</span>
                                <span class="news-ticker-item">Tech College of Skills Development &amp; Placement &mdash; Building skills, building futures</span>
                            @endforelse
                        </div>
                    @endfor
                </div>
            </div>
            <button class="ticker-toggle" type="button" aria-label="Pause news ticker" aria-pressed="false"><span class="ticker-pause-symbol" aria-hidden="true">&#10074;&#10074;</span></button>
            <a class="news-ticker-contact" href="tel:0514627600"><i data-lucide="phone-call"></i><span>Registration: 051-4627600</span></a>
        </section>
        <section class="section courses-section" id="courses" aria-labelledby="courses-title">
            <div class="container">
                <div class="course-list-heading">
                    <h2 id="courses-title">Top Courses List</h2>
                </div>
                @include('partials.course-slider', ['sliderCourses' => $courses, 'sliderId' => 'popular-courses', 'sliderLabel' => 'Popular courses'])
            </div>
        </section>
        <section class="section about" id="about"><div class="container about-grid"><div class="campus-art"><img src="{{ asset('data/campus-building.png') }}" alt="Tech College campus"></div><div class="about-copy"><h2>Why Choose Tech College?</h2><p class="lead">Your Skills. Our Mission.</p><ul><li><i data-lucide="circle-check"></i> Hands-on, practical and industry-focused training</li><li><i data-lucide="circle-check"></i> Experienced and qualified trainers</li><li><i data-lucide="circle-check"></i> 100% placement support</li><li><i data-lucide="circle-check"></i> Recognized certification</li><li><i data-lucide="circle-check"></i> Career-oriented learning environment</li></ul></div><blockquote><i data-lucide="quote"></i><p>Skills create<br>opportunities.</p><cite>Tech College</cite></blockquote></div></section>

        {{-- News & Events Carousel --}}
        <section class="news-section-home" id="news-events">
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
                                <img src="{{ asset($newsEvent->image_path ?: 'data/campus-building.png') }}" alt="{{ $newsEvent->title }}">
                            </div>
                            <div class="news-card-v2-body">
                                @if($newsEvent->event_date)
                                    <span class="news-card-v2-date"><i data-lucide="calendar-days"></i> {{ $newsEvent->event_date->format('M d, Y') }}</span>
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

        <section class="container stats">@foreach ($stats as $stat)<article><i data-lucide="{{ $stat['icon'] }}"></i><div><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div></article>@endforeach</section>
        {{-- Student stories near the bottom of the homepage --}}
        <section class="testimonials-cards-section" id="placement">
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
                                <img src="{{ asset($testimonial->image_path ?: 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="{{ $testimonial->student_name }}">
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

        <section class="cta container" id="admissions"><div><i data-lucide="graduation-cap"></i><div><h2>Start your skill journey today</h2><p>Join Tech College and build a brighter tomorrow.</p></div></div><a class="button button-gold" href="#courses">Apply Now <i data-lucide="arrow-right"></i></a></section>
    </main>
    <footer id="contact"><div class="container footer-grid"><div class="footer-brand"><a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a><p>Empowering youth with skills, knowledge and opportunities to build a better future.</p></div><div><h3>Quick links</h3><a href="#about">About us</a><a href="#courses">Courses</a><a href="#admissions">Admissions</a><a href="#placement">Placement</a></div><div><h3>Our programs</h3><a href="#courses">Technical skills</a><a href="#courses">IT & digital skills</a><a href="#courses">Vocational training</a><a href="#courses">Soft skills</a></div><div><h3>Get in touch</h3><p><i data-lucide="map-pin"></i> Hakim Khan Plaza,<br>Main GT Road, Rawat,<br>Rawalpindi, Pakistan</p><p><i data-lucide="phone"></i> 051-4627600</p><p><i data-lucide="mail"></i> techcollegepak@gmail.com</p></div></div><div class="copyright">&copy; {{ date('Y') }} Tech College of Skills Development & Placement. All rights reserved.</div></footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/course-slider.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/hero-banners.js') }}?v={{ filemtime(public_path('js/hero-banners.js')) }}"></script>
    @if (session('status'))
        <script>if (window.Swal) Swal.fire({icon: 'success', title: 'Request Sent', text: @json(session('status')), confirmButtonColor: '#063d2b'});</script>
    @endif
</body>
</html>
