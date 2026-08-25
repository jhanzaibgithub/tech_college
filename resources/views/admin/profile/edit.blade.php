@extends('admin.layout')

@section('title', 'Profile')

@section('content')
    <form class="admin-card admin-profile-form" method="POST" action="{{ route('admin.profile.update') }}">
        @csrf @method('PUT')
        <div class="admin-card-head">
            <div><h2>Admin Profile</h2><p>Update your account information and password.</p></div>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save Profile</button>
        </div>
        <div class="admin-fields">
            <label>Name <input type="text" name="name" value="{{ old('name', $admin->name) }}" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email', $admin->email) }}" required></label>
        </div>
        <div class="admin-fields">
            <label>Current Password <input type="password" name="current_password"></label>
            <label>New Password <input type="password" name="password"></label>
            <label>Confirm Password <input type="password" name="password_confirmation"></label>
        </div>
        @if ($errors->any())
            <div class="admin-error-list">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </form>
@endsection
