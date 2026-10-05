@php($homeLink = request()->routeIs('home') ? '' : route('home'))
<header class="site-header home-header">
    <div class="navigation-band">
        <div class="container navigation-inner">
            <a href="{{ route('home') }}" class="brand"><img src="{{ asset('data/logo-crest.jpg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a>
            <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="main-navigation" aria-expanded="false"><i data-lucide="menu"></i></button>
            <nav id="main-navigation" aria-label="Main navigation">
                <a @class(['active' => request()->routeIs('home')]) href="{{ route('home') }}">Home</a>
                <a @class(['active' => request()->routeIs('about')]) href="{{ route('about') }}">About Us</a>
                <a @class(['active' => request()->routeIs('courses.*')]) href="{{ route('courses.index') }}">Courses</a>
                <a href="{{ route('home') }}#news-events">News & Events</a>
                <a @class(['active' => request()->routeIs('contact')]) href="{{ route('contact') }}">Contact Us</a>
                <button class="button button-gold nav-cta" type="button" data-open-enrollment>Enrolled now <i data-lucide="arrow-up-right"></i></button>
            </nav>
        </div>
    </div>
</header>
