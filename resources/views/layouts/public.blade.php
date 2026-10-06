<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Tech College of Skills Development and Placement helps students build job-ready skills.')">
    <title>@yield('title', 'Tech College | Skills Development & Placement')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/logo-crest.jpg') }}">
    <script>document.documentElement.classList.add("js")</script>
    <script src="{{ asset('js/img-retry.js') }}?v={{ filemtime(public_path('js/img-retry.js')) }}"></script>
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}?v={{ filemtime(public_path('css/loader.css')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/navigation.css') }}?v={{ filemtime(public_path('css/navigation.css')) }}">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}?v={{ filemtime(public_path('css/premium.css')) }}">
</head>
<body>
    @include('partials.page-loader')
    <script src="{{ asset('js/loader.js') }}?v={{ filemtime(public_path('js/loader.js')) }}"></script>
    @include('partials.topbar')
    @include('partials.site-header')
    <main id="home">
        @yield('content')
    </main>
    @include('partials.site-footer')
    @include('partials.enroll-modal')
    @include('partials.whatsapp-float')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    @stack('vendor-scripts')
    <script src="{{ asset('js/navigation.js') }}?v={{ filemtime(public_path('js/navigation.js')) }}"></script>
    <script src="{{ asset('js/premium.js') }}?v={{ filemtime(public_path('js/premium.js')) }}"></script>
    @stack('scripts')
    @if (session('status'))
        <script>if (window.Swal) Swal.fire({icon: 'success', title: 'Thank you', text: @json(session('status')), confirmButtonColor: '#063d2b'});</script>
    @endif
</body>
</html>
