@php
    $enrollCourses = \App\Models\Course::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(['id', 'title', 'slug']);
    $currentSlug = request()->route('slug');
    $selectedCourse = old('course_id', $enrollCourses->firstWhere('slug', $currentSlug)?->id);
    $errs = $errors->getBag('enroll');
@endphp
<div class="enrollment-modal" data-enrollment-modal aria-hidden="true" data-has-errors="{{ $errs->any() ? '1' : '0' }}">
    <div class="enrollment-modal-backdrop" data-close-enrollment></div>
    <div class="enrollment-modal-panel" role="dialog" aria-modal="true" aria-labelledby="enrollment-title">
        <button class="enrollment-modal-close" type="button" data-close-enrollment aria-label="Close enrollment form"><i data-lucide="x"></i></button>
        <p class="modal-kicker">Admissions</p>
        <h2 id="enrollment-title">Enroll now</h2>
        <p class="modal-lead">Choose your course and share your details. Our admissions team will contact you.</p>
        @if ($errs->any())
            <div class="admin-error-list" role="alert">
                @foreach ($errs->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form class="enrollment-form" method="POST" action="{{ route('enroll.store') }}" data-enroll-form>
            @csrf
            <label for="enroll-course">Course</label>
            <select id="enroll-course" name="course_id" required @disabled($enrollCourses->isEmpty())>
                <option value="">{{ $enrollCourses->isEmpty() ? 'No courses available right now' : 'Select a course' }}</option>
                @foreach ($enrollCourses as $option)
                    <option value="{{ $option->id }}" @selected((string) $selectedCourse === (string) $option->id)>{{ $option->title }}</option>
                @endforeach
            </select>
            <label for="enroll-name">Full name</label>
            <input id="enroll-name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required>
            <label for="enroll-phone">Mobile number</label>
            <input id="enroll-phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" autocomplete="tel" maxlength="11" pattern="03[0-4][0-9]{8}" placeholder="03XXXXXXXXX" data-pk-phone required>
            <small class="field-error" data-phone-error hidden>Enter an 11-digit Pakistani mobile number, e.g. 03001234567.</small>
            <label for="enroll-email">Email <span>(optional)</span></label>
            <input id="enroll-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255">
            <label for="enroll-message">Message <span>(optional)</span></label>
            <textarea id="enroll-message" name="message" rows="3" maxlength="1000">{{ old('message') }}</textarea>
            <button class="button button-gold enroll-submit" type="submit" @disabled($enrollCourses->isEmpty())>Apply <i data-lucide="arrow-right"></i></button>
        </form>
    </div>
</div>
