<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Tech College</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College">
            <span>TECH COLLEGE<small>Admin Panel</small></span>
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><i data-lucide="layout-dashboard"></i> Dashboard</a>
            <a href="{{ route('admin.courses.index') }}" @class(['active' => request()->routeIs('admin.courses.*')])><i data-lucide="book-open"></i> Courses</a>
            <div @class(['admin-nav-dropdown', 'active' => request()->routeIs('admin.enrollments.*')])>
                <button type="button" data-sidebar-dropdown>
                    <span><i data-lucide="user-plus"></i> {{ request()->routeIs('admin.enrollments.*') ? match(request('status', 'all')) { 'new' => 'New Requests', 'confirmed' => 'Confirmed Students', 'completed' => 'Completed Students', default => 'All Enrollments' } : 'Enrollments' }}</span>
                    <i data-lucide="chevron-down"></i>
                </button>
                <div class="admin-nav-dropdown-menu">
                    <a href="{{ route('admin.enrollments.index') }}">All Enrollments</a>
                    <a href="{{ route('admin.enrollments.index', ['status' => 'new']) }}">New Requests</a>
                    <a href="{{ route('admin.enrollments.index', ['status' => 'confirmed']) }}">Confirmed Students</a>
                    <a href="{{ route('admin.enrollments.index', ['status' => 'completed']) }}">Completed Students</a>
                </div>
            </div>
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
                        <img src="{{ asset(auth('admin')->user()->profile_image ?: 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Admin">
                        <span>{{ collect(explode(' ', auth('admin')->user()->name))->take(2)->join(' ') }}</span>
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
        document.querySelector('[data-admin-menu]')?.addEventListener('click',()=>document.querySelector('[data-admin-dropdown]')?.classList.toggle('open'));
        document.querySelector('[data-sidebar-dropdown]')?.addEventListener('click',()=>document.querySelector('.admin-nav-dropdown')?.classList.toggle('open'));
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
    @stack('scripts')
</body>
</html>
