<footer id="contact">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a>
            <p>{{ \Illuminate\Support\Str::limit($site['about']['description'], 150) }}</p>
        </div>
        <div>
            <h3>Quick links</h3>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About us</a>
            <a href="{{ route('courses.index') }}">Courses</a>
            <a href="{{ route('contact') }}">Contact us</a>
            <button class="footer-link-button" type="button" data-open-enrollment>Enrolled now</button>
        </div>
        <div>
            <h3>Our programs</h3>
            @forelse ($footerCourses as $footerCourse)
                <a href="{{ route('courses.show', $footerCourse->slug) }}">{{ $footerCourse->title }}</a>
            @empty
                <a href="{{ route('courses.index') }}">Browse all courses</a>
            @endforelse
        </div>
        <div>
            <h3>Get in touch</h3>
            <p><i data-lucide="map-pin"></i> {!! nl2br(e($site['address'])) !!}</p>
            <p><i data-lucide="phone"></i> <a href="{{ $site['phone_href'] }}">{{ $site['phone'] }}</a></p>
            <p><i data-lucide="mail"></i> <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></p>
            @include('partials.social-icons', ['class' => 'footer-social'])
        </div>
    </div>
    <div class="copyright">&copy; {{ date('Y') }} Tech College of Skills Development & Placement. All rights reserved.</div>
</footer>
