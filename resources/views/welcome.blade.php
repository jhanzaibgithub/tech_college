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
</head>
<body>
    <div class="topbar"><div class="container topbar-inner"><span><i data-lucide="mail"></i> techcollegepak@gmail.com</span><span><i data-lucide="phone"></i> 051-4627600</span><span class="follow">Follow Us: <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a><a href="#" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a><a href="#" aria-label="YouTube"><i data-lucide="youtube"></i></a></span></div></div>
    <header class="site-header"><div class="container nav-wrap"><a href="#home" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a><button class="menu-toggle" aria-label="Open menu"><i data-lucide="menu"></i></button><nav><a class="active" href="#home">Home</a><a href="#about">About Us</a><a href="#courses">Courses</a><a href="#admissions">Admissions</a><a href="#placement">Placement</a><a href="#about">Why Us?</a><a href="#contact">Contact Us</a><a class="button button-small" href="#courses">Apply Now <i data-lucide="arrow-up-right"></i></a></nav></div></header>
    <main id="home">
        <section class="hero"><div class="container hero-grid"><div class="hero-copy"><p class="eyebrow">BUILD SKILLS <b>&bull;</b> GAIN CONFIDENCE <b>&bull;</b> GET EMPLOYED</p><h1>Build your skills<br>Build your <em>future</em></h1><p class="hero-text">Tech College of Skills Development & Placement (TCSDP) empowers youth with practical training and 100% placement support to build successful careers.</p><div class="hero-actions"><a class="button" href="#courses">Explore Courses <i data-lucide="arrow-right"></i></a><a class="button button-outline" href="#admissions">Admissions Open <i data-lucide="graduation-cap"></i></a></div></div><div class="hero-art"><div class="placement-badge"><i data-lucide="users-round"></i><strong>100<span>%</span></strong><small>PLACEMENT<br>SUPPORT</small></div></div></div></section>
        <section class="feature-strip container">@foreach ($features as $feature)<article><span class="feature-icon"><i data-lucide="{{ $feature['icon'] }}"></i></span><h3>{{ $feature['title'] }}</h3><p>{{ $feature['text'] }}</p></article>@endforeach</section>
        <section class="section courses-section" id="courses"><div class="container"><div class="section-heading courses-heading"><span></span><div><h2>Our Popular Courses</h2><p>Industry-Relevant Skills for a Successful Future</p></div><span></span></div><div class="course-grid course-carousel owl-carousel owl-theme">@foreach ($courses as $course)<article class="course-card"><div class="course-visual"><div class="course-image-carousel owl-carousel owl-theme">@forelse ($course->images as $image)<img src="{{ asset($image->path) }}" alt="{{ $image->alt_text ?? $course->title }}">@empty<img src="{{ asset('data/courses/technical-skills.png') }}" alt="{{ $course->title }}">@endforelse</div><span class="course-icon"><i data-lucide="{{ $course->icon }}"></i></span></div><div class="course-body"><h3>{{ $course->title }}</h3><p>{{ $course->short_description }}</p><a href="{{ route('courses.show', $course->slug) }}">View Course <i data-lucide="arrow-right"></i></a></div></article>@endforeach</div></div></section>
        <section class="section about" id="about"><div class="container about-grid"><div class="campus-art"><img src="{{ asset('data/campus-building.png') }}" alt="Tech College campus"></div><div class="about-copy"><h2>Why Choose Tech College?</h2><p class="lead">Your Skills. Our Mission.</p><ul><li><i data-lucide="circle-check"></i> Hands-on, practical and industry-focused training</li><li><i data-lucide="circle-check"></i> Experienced and qualified trainers</li><li><i data-lucide="circle-check"></i> 100% placement support</li><li><i data-lucide="circle-check"></i> Recognized certification</li><li><i data-lucide="circle-check"></i> Career-oriented learning environment</li></ul></div><blockquote><i data-lucide="quote"></i><p>Skills create<br>opportunities.</p><cite>Tech College</cite></blockquote></div></section>
        <section class="student-news-section" id="placement">
            <div class="container student-news-grid">
                <div class="student-panel">
                    <h2>What Our Students Say</h2>
                    <div class="testimonial-carousel owl-carousel owl-theme">
                        @foreach ($testimonials as $testimonial)
                            <article class="testimonial-card">
                                <i class="testimonial-quote" data-lucide="quote"></i>
                                <p>{{ $testimonial->message }}</p>
                                <div class="testimonial-author">
                                    <img src="{{ asset($testimonial->image_path ?: 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="{{ $testimonial->student_name }}">
                                    <div><strong>{{ $testimonial->student_name }}</strong><span>{{ $testimonial->designation ?: 'Student' }}</span></div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
                <div class="student-panel">
                    <h2>Latest News & Events</h2>
                    <div class="news-events-carousel owl-carousel owl-theme">
                        @foreach ($newsEvents as $newsEvent)
                            <article class="home-news-item">
                                <img src="{{ asset($newsEvent->image_path ?: 'data/campus-building.png') }}" alt="{{ $newsEvent->title }}">
                                <div>
                                    <h3>{{ $newsEvent->title }}</h3>
                                    <p>{{ $newsEvent->summary }}</p>
                                    @if ($newsEvent->event_date)<span><i data-lucide="calendar-days"></i> {{ $newsEvent->event_date->format('M d, Y') }}</span>@endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <section class="container stats">@foreach ($stats as $stat)<article><i data-lucide="{{ $stat['icon'] }}"></i><div><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div></article>@endforeach</section>
        <section class="cta container" id="admissions"><div><i data-lucide="graduation-cap"></i><div><h2>Start your skill journey today</h2><p>Join Tech College and build a brighter tomorrow.</p></div></div><a class="button button-gold" href="#courses">Apply Now <i data-lucide="arrow-right"></i></a></section>
    </main>
    <footer id="contact"><div class="container footer-grid"><div class="footer-brand"><a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a><p>Empowering youth with skills, knowledge and opportunities to build a better future.</p></div><div><h3>Quick links</h3><a href="#about">About us</a><a href="#courses">Courses</a><a href="#admissions">Admissions</a><a href="#placement">Placement</a></div><div><h3>Our programs</h3><a href="#courses">Technical skills</a><a href="#courses">IT & digital skills</a><a href="#courses">Vocational training</a><a href="#courses">Soft skills</a></div><div><h3>Get in touch</h3><p><i data-lucide="map-pin"></i> Hakim Khan Plaza,<br>Main GT Road, Rawat,<br>Rawalpindi, Pakistan</p><p><i data-lucide="phone"></i> 051-4627600</p><p><i data-lucide="mail"></i> techcollegepak@gmail.com</p></div></div><div class="copyright">&copy; {{ date('Y') }} Tech College of Skills Development & Placement. All rights reserved.</div></footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script><script>lucide.createIcons();document.querySelector('.menu-toggle')?.addEventListener('click',()=>document.querySelector('.nav-wrap nav')?.classList.toggle('open'));document.querySelectorAll('.nav-wrap nav a').forEach((link)=>link.addEventListener('click',()=>document.querySelector('.nav-wrap nav')?.classList.remove('open')));@if (session('status'))Swal.fire({icon:'success',title:'Request Sent',text:@json(session('status')),confirmButtonColor:'#063d2b'});@endif if(window.jQuery&&jQuery.fn.owlCarousel){const courseCount=jQuery('.course-carousel > .course-card').length;if(courseCount>5){jQuery('.course-carousel').owlCarousel({loop:true,margin:28,nav:false,dots:true,autoplay:true,autoplayTimeout:3500,autoplayHoverPause:true,responsive:{0:{items:1,margin:14},560:{items:2,margin:18},900:{items:3,margin:22},1180:{items:5,margin:28}}});}jQuery('.course-image-carousel').each(function(){const imageCount=jQuery(this).find('img').length;jQuery(this).owlCarousel({items:1,loop:imageCount>1,nav:false,dots:false,autoplay:imageCount>1,autoplayTimeout:2600,mouseDrag:false,touchDrag:false,animateOut:'fadeOut'});});const testimonialCount=jQuery('.testimonial-carousel .testimonial-card').length;if(testimonialCount){jQuery('.testimonial-carousel').owlCarousel({items:1,loop:testimonialCount>1,nav:false,dots:true,autoplay:testimonialCount>1,autoplayTimeout:3200,autoplayHoverPause:true,smartSpeed:650});}const newsCount=jQuery('.news-events-carousel .home-news-item').length;if(newsCount){jQuery('.news-events-carousel').owlCarousel({items:1,loop:newsCount>1,nav:false,dots:newsCount>1,autoplay:newsCount>1,autoplayTimeout:3400,autoplayHoverPause:true,smartSpeed:650});}window.addEventListener('load',()=>jQuery('.testimonial-carousel,.news-events-carousel').trigger('refresh.owl.carousel'));lucide.createIcons();}</script>
</body>
</html>
