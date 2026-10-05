<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Tech College</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/logo-crest.jpg') }}">
    <script>document.documentElement.classList.add("js")</script>
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}?v={{ filemtime(public_path('css/loader.css')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-extra.css') }}?v={{ filemtime(public_path('css/admin-extra.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-table.css') }}?v={{ filemtime(public_path('css/admin-table.css')) }}">
</head>
<body class="admin-body">
    @include('partials.page-loader', ['subtitle' => 'Admin Panel'])
    <script src="{{ asset('js/loader.js') }}?v={{ filemtime(public_path('js/loader.js')) }}"></script>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('data/logo-crest.jpg') }}" alt="Tech College">
            <span>TECH COLLEGE<small>Admin Panel</small></span>
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><i data-lucide="layout-dashboard"></i> Dashboard</a>
            <a href="{{ route('admin.banners.index') }}" @class(['active' => request()->routeIs('admin.banners.*')])><i data-lucide="images"></i> Banners</a>
            <a href="{{ route('admin.courses.index') }}" @class(['active' => request()->routeIs('admin.courses.*')])><i data-lucide="book-open"></i> Courses</a>
            <a href="{{ route('admin.enrollments.index') }}" @class(['active' => request()->routeIs('admin.enrollments.*')])><i data-lucide="user-plus"></i> Enrollments @if(($newEnrollments = \App\Models\Enrollment::where('status', 'new')->count()) > 0)<span class="admin-badge" title="New requests">{{ $newEnrollments }}</span>@endif</a>
            <a href="{{ route('admin.testimonials.index') }}" @class(['active' => request()->routeIs('admin.testimonials.*')])><i data-lucide="message-square-quote"></i> Testimonials</a>
            <a href="{{ route('admin.news-events.index') }}" @class(['active' => request()->routeIs('admin.news-events.*')])><i data-lucide="newspaper"></i> News & Events</a>
            <p class="admin-nav-label">Site content</p>
            <a href="{{ route('admin.settings.edit', 'hero') }}" @class(['active' => request()->is('admin/settings/hero')])><i data-lucide="sparkles"></i> Homepage Hero</a>
            <a href="{{ route('admin.settings.edit', 'pages') }}" @class(['active' => request()->is('admin/settings/pages')])><i data-lucide="image"></i> Page Heroes</a>
            <a href="{{ route('admin.settings.edit', 'stats') }}" @class(['active' => request()->is('admin/settings/stats')])><i data-lucide="chart-bar"></i> Statistics</a>
            <a href="{{ route('admin.settings.edit', 'about') }}" @class(['active' => request()->is('admin/settings/about')])><i data-lucide="info"></i> About Us</a>
            <a href="{{ route('admin.settings.edit', 'contact') }}" @class(['active' => request()->is('admin/settings/contact')])><i data-lucide="phone"></i> Contact & Social</a>
            <a href="{{ route('admin.messages.index') }}" @class(['active' => request()->routeIs('admin.messages.*')])><i data-lucide="mail"></i> Messages @if(($unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count()) > 0)<span class="admin-badge">{{ $unreadMessages }}</span>@endif</a>
        </nav>
    </aside>
    <div class="admin-shell">
        <header class="admin-topbar">
            <div>
                <strong>@yield('title', 'Dashboard')</strong>
                <span>Manage Tech College content</span>
            </div>
            <div class="admin-header-actions">
                <a class="admin-view-site" href="{{ route('home') }}" target="_blank"><i data-lucide="external-link"></i> View Site</a>
                <div class="admin-user-menu">
                    <button type="button" data-admin-menu>
                        <img src="{{ \App\Support\Media::url(auth('admin')->user()?->profile_image, \App\Support\Media::AVATAR_FALLBACK) }}" alt="Admin">
                        <span>{{ collect(explode(' ', (string) auth('admin')->user()?->name))->take(2)->join(' ') ?: 'Admin' }}</span>
                        <i data-lucide="chevron-down"></i>
                    </button>
                    <div class="admin-user-dropdown" data-admin-dropdown>
                        <a href="{{ route('admin.profile.edit') }}"><i data-lucide="user-cog"></i> Profile</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"><i data-lucide="log-out"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="admin-main">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();
        document.querySelector('.admin-nav a.active')?.scrollIntoView({ inline: 'center', block: 'nearest' });
        document.querySelector('[data-admin-menu]')?.addEventListener('click',()=>document.querySelector('[data-admin-dropdown]')?.classList.toggle('open'));
        @if (session('status'))
            Swal.fire({icon:'success',title:'Success',text:@json(session('status')),confirmButtonColor:'#063d2b'});
        @endif
        document.querySelectorAll('[data-confirm]').forEach((form)=>{
            form.addEventListener('submit',(event)=>{
                event.preventDefault();
                Swal.fire({icon:'warning',title:'Are you sure?',text:form.dataset.confirm,showCancelButton:true,confirmButtonColor:'#063d2b',cancelButtonColor:'#87928d',confirmButtonText:'Yes, continue'}).then((result)=>{if(result.isConfirmed) form.submit();});
            });
        });
    </script>
    <script src="{{ asset('js/image-preview.js') }}"></script>
    @stack('scripts')
</body>
</html>
