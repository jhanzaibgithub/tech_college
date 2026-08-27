<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Tech College</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-login-body">
    <main class="admin-login-card">
        <img src="{{ asset('data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Tech College">
        <h1>Admin Login</h1>
        <p>Sign in to manage courses and website content.</p>
        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', 'admin@techcollege.com.pk') }}" required autofocus>
            @error('email')<small>{{ $message }}</small>@enderror
            <label>Password</label>
            <input type="password" name="password" value="password" required>
            @error('password')<small>{{ $message }}</small>@enderror
            <label class="admin-check"><input type="checkbox" name="remember"> Remember me</label>
            <button type="submit">Login <i data-lucide="arrow-right"></i></button>
        </form>
    </main>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script><script>lucide.createIcons();</script>
</body>
</html>
