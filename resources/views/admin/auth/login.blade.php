<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Tech College</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/logo-crest.jpg') }}">
    <script>document.documentElement.classList.add("js")</script>
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}?v={{ filemtime(public_path('css/loader.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-extra.css') }}?v={{ filemtime(public_path('css/admin-extra.css')) }}">
</head>
<body class="admin-login-body">
    @include('partials.page-loader', ['subtitle' => 'Admin Panel'])
    <script src="{{ asset('js/loader.js') }}?v={{ filemtime(public_path('js/loader.js')) }}"></script>
    <main class="admin-login-card">
        <img src="{{ asset('data/logo-crest.jpg') }}" alt="Tech College">
        <h1>Admin Login</h1>
        <p>Sign in to manage courses and website content.</p>
        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <label for="admin-email">Email</label>
            <input id="admin-email" type="email" name="email" autocomplete="username" value="{{ old('email', 'admin@techcollege.com.pk') }}" required autofocus>
            @error('email')<small>{{ $message }}</small>@enderror
            <label for="admin-password">Password</label>
            <div class="password-field">
                <input id="admin-password" type="password" name="password" autocomplete="current-password" required>
                <button type="button" class="password-toggle" data-toggle-password aria-label="Show password" aria-pressed="false" aria-controls="admin-password">
                    <i data-lucide="eye" data-eye-on></i><i data-lucide="eye-off" data-eye-off hidden></i>
                </button>
            </div>
            @error('password')<small>{{ $message }}</small>@enderror
            <label class="admin-check"><input type="checkbox" name="remember"> Remember me</label>
            <button type="submit">Login <i data-lucide="arrow-right"></i></button>
        </form>
    </main>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script><script>
        lucide.createIcons();
        document.querySelector('[data-toggle-password]')?.addEventListener('click', (event) => {
            const button = event.currentTarget;
            const input = document.getElementById(button.getAttribute('aria-controls'));
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(show));
            button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            button.querySelector('[data-eye-on]').hidden = show;
            button.querySelector('[data-eye-off]').hidden = !show;
            input.focus();
        });
    </script>
</body>
</html>
