@extends('admin.layout')

@section('title', 'Profile')

@section('content')
    <form class="admin-card admin-profile-form" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="admin-card-head">
            <div><h2>Admin Profile</h2><p>Update your account information and password.</p></div>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save Profile</button>
        </div>
        <div class="admin-profile-image">
            <img src="{{ asset($admin->profile_image ?: 'data/WhatsApp Image 2026-08-23 at 3.36.55 PM.jpeg') }}" alt="Admin profile image" data-profile-preview>
            <label>Profile Image <input type="file" name="profile_image" accept="image/*" data-profile-image></label>
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

@push('scripts')
<script>
    const profileInput = document.querySelector('[data-profile-image]');
    const profilePreview = document.querySelector('[data-profile-preview]');

    profileInput?.addEventListener('change', () => {
        const file = profileInput.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            profilePreview.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
