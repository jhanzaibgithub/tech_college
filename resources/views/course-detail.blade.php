<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $course->title }} at Tech College of Skills Development and Placement.">
    <title>{{ $course->title }} | Tech College</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="topbar"><div class="container topbar-inner"><span><i data-lucide="mail"></i> info@techcollege.com.pk</span><span><i data-lucide="phone"></i> 051-4627600</span><span class="follow">Follow Us: <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a><a href="#" aria-label="LinkedIn"><i data-lucide="linkedin"></i></a><a href="#" aria-label="YouTube"><i data-lucide="youtube"></i></a></span></div></div>
    <header class="site-header"><div class="container nav-wrap"><a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a><button class="menu-toggle" aria-label="Open menu"><i data-lucide="menu"></i></button><nav><a href="{{ route('home') }}">Home</a><a href="{{ route('home') }}#about">About Us</a><a class="active" href="{{ route('home') }}#courses">Courses</a><a href="{{ route('home') }}#admissions">Admissions</a><a href="{{ route('home') }}#placement">Placement</a><a href="{{ route('home') }}#about">Why Us?</a><a href="{{ route('home') }}#contact">Contact Us</a><a class="button button-small" href="{{ route('home') }}#admissions">Apply Now <i data-lucide="arrow-up-right"></i></a></nav></div></header>
    <main>
        <section class="course-detail-hero">
            <div class="container course-detail-grid">
                <div class="course-detail-copy">
                    <p class="detail-kicker"><i data-lucide="{{ $course->icon }}"></i> {{ $course->title }}</p>
                    <h1>{{ $course->title }}</h1>
                    <p>{{ $course->overview }}</p>
                    <div class="hero-actions"><a class="button" href="{{ route('home') }}#admissions">Apply Now <i data-lucide="arrow-right"></i></a><a class="button button-outline" href="{{ route('home') }}#courses">Back to Courses <i data-lucide="arrow-left"></i></a></div>
                </div>
                <div class="detail-carousel owl-carousel owl-theme">
                    @foreach ($gallery as $image)
                        <div class="detail-slide"><img src="{{ asset($image) }}" alt="{{ $course->title }} training image"></div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="section detail-section">
            <div class="container detail-content-grid">
                <article class="detail-panel">
                    <h2>Course Details</h2>
                    <p>{{ $course->overview }}</p>
                    @if ($course->details)
                        <div class="detail-rich">{!! $course->details !!}</div>
                    @endif
                    <ul>
                        <li><i data-lucide="circle-check"></i>Hands-on practical training</li>
                        <li><i data-lucide="circle-check"></i>Experienced instructors</li>
                        <li><i data-lucide="circle-check"></i>Recognized certification support</li>
                        <li><i data-lucide="circle-check"></i>Placement-focused preparation</li>
                    </ul>
                </article>
                <aside class="detail-panel detail-card">
                    <h3>Request Enrollment</h3>
                    <p>Share your details and our team will contact you about this course.</p>
                    <form class="enrollment-form" method="POST" action="{{ route('courses.enroll', $course->slug) }}">
                        @csrf
                        <input type="text" name="name" placeholder="Full name" required>
                        <input type="tel" name="phone" placeholder="Phone number" required>
                        <input type="email" name="email" placeholder="Email address">
                        <textarea name="message" rows="3" placeholder="Message"></textarea>
                        <button class="button" type="submit">Send Request <i data-lucide="arrow-right"></i></button>
                    </form>
                </aside>
            </div>
        </section>
        <section class="section courses-section related-courses">
            <div class="container">
                <div class="section-heading courses-heading"><span></span><div><h2>Related Courses</h2><p>Explore More Skill-Focused Programs</p></div><span></span></div>
                <div class="course-grid course-carousel owl-carousel owl-theme">
                    @foreach ($courses as $item)
                        <article class="course-card"><div class="course-visual"><div class="course-image-carousel owl-carousel owl-theme">@forelse ($item->images as $image)<img src="{{ asset($image->path) }}" alt="{{ $image->alt_text ?? $item->title }}">@empty<img src="{{ asset('data/courses/technical-skills.png') }}" alt="{{ $item->title }}">@endforelse</div><span class="course-icon"><i data-lucide="{{ $item->icon }}"></i></span></div><div class="course-body"><h3>{{ $item->title }}</h3><p>{{ $item->short_description }}</p><a href="{{ route('courses.show', $item->slug) }}">View Course <i data-lucide="arrow-right"></i></a></div></article>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
    <footer id="contact"><div class="container footer-grid"><div class="footer-brand"><a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a><p>Empowering youth with skills, knowledge and opportunities to build a better future.</p></div><div><h3>Quick links</h3><a href="{{ route('home') }}#about">About us</a><a href="{{ route('home') }}#courses">Courses</a><a href="{{ route('home') }}#admissions">Admissions</a><a href="{{ route('home') }}#placement">Placement</a></div><div><h3>Our programs</h3><a href="{{ route('home') }}#courses">Technical skills</a><a href="{{ route('home') }}#courses">IT & digital skills</a><a href="{{ route('home') }}#courses">Vocational training</a><a href="{{ route('home') }}#courses">Soft skills</a></div><div><h3>Get in touch</h3><p><i data-lucide="map-pin"></i> Street 1, Rawat,<br>Islamabad, Pakistan</p><p><i data-lucide="phone"></i> 051-4627600</p><p><i data-lucide="mail"></i> info@techcollege.com.pk</p></div></div><div class="copyright">&copy; {{ date('Y') }} Tech College of Skills Development & Placement. All rights reserved.</div></footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script><script>lucide.createIcons();@if (session('status'))Swal.fire({icon:'success',title:'Request Sent',text:@json(session('status')),confirmButtonColor:'#063d2b'});@endif if(window.jQuery&&jQuery.fn.owlCarousel){const detailCount=jQuery('.detail-carousel .detail-slide').length;jQuery('.detail-carousel').owlCarousel({items:1,loop:detailCount>1,nav:detailCount>1,dots:detailCount>1,autoplay:detailCount>1,autoplayTimeout:3600,autoplayHoverPause:true});const courseCount=jQuery('.course-carousel > .course-card').length;if(courseCount>5){jQuery('.course-carousel').owlCarousel({loop:true,margin:28,nav:false,dots:true,autoplay:true,autoplayTimeout:3500,autoplayHoverPause:true,responsive:{0:{items:1,margin:14},560:{items:2,margin:18},900:{items:3,margin:22},1180:{items:5,margin:28}}});}jQuery('.course-image-carousel').each(function(){const imageCount=jQuery(this).find('img').length;jQuery(this).owlCarousel({items:1,loop:imageCount>1,nav:false,dots:false,autoplay:imageCount>1,autoplayTimeout:2600,mouseDrag:false,touchDrag:false,animateOut:'fadeOut'});});lucide.createIcons();}</script>
</body>
</html>
