@extends('admin.layout')

@section('title', $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif
        <section class="admin-card">
            <div class="admin-card-head">
                <div><h2>{{ $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial' }}</h2><p>Manage student feedback shown on the home page.</p></div>
            </div>

            <div class="admin-fields">
                <label>Student Name <input type="text" name="student_name" value="{{ old('student_name', $testimonial->student_name) }}" required></label>
                <label>Designation <input type="text" name="designation" value="{{ old('designation', $testimonial->designation) }}" placeholder="Web Developer"></label>
            </div>

            <div class="admin-full">
                <label>Message <textarea name="message" rows="5" maxlength="1000" required>{{ old('message', $testimonial->message) }}</textarea></label>
            </div>

            <div class="admin-fields">
                <label>Student Photo <input type="file" name="image" accept="image/*"></label>
                <label class="admin-check admin-active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true))> Active testimonial</label>
            </div>

            @if ($testimonial->image_path)
                <div class="admin-current-image">
                    <img src="{{ asset($testimonial->image_path) }}" alt="{{ $testimonial->student_name }}">
                    <span>Current photo</span>
                </div>
            @endif
        </section>
        <div class="admin-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.testimonials.index') }}">Cancel</a>
            <button class="admin-button" type="submit"><i data-lucide="save"></i> Save Testimonial</button>
        </div>
    </form>
@endsection
