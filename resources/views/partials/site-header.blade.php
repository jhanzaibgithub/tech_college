@php($homeLink = request()->routeIs('home') ? '' : route('home'))
<header class="site-header home-header">
    <div class="navigation-band">
        <div class="container navigation-inner">
            <a href="{{ $homeLink }}#home" class="brand"><img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College crest"><span><strong>TECH COLLEGE</strong><small>OF SKILLS DEVELOPMENT<br>& PLACEMENT</small></span></a>
            <button class="menu-toggle" aria-label="Open menu" aria-controls="main-navigation" aria-expanded="false"><i data-lucide="menu"></i></button>
            <nav id="main-navigation" aria-label="Main navigation">
                <a @class(['active' => request()->routeIs('home')]) href="{{ $homeLink }}#home">Home</a>
                <a href="{{ $homeLink }}#about">About Us</a>
                <a @class(['active' => request()->routeIs('courses.show')]) href="{{ $homeLink }}#courses">Courses</a>
                <a href="{{ $homeLink }}#placement">Student Stories</a>
                <a href="{{ $homeLink }}#contact">Contact Us</a>
                <a class="button button-gold" href="{{ $homeLink }}#admissions">Online Admission <i data-lucide="arrow-up-right"></i></a>
            </nav>
        </div>
    </div>
</header>
