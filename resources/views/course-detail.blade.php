@extends('layouts.public')

@section('title', $course->title . ' | Tech College')
@section('description', $course->title . ' at Tech College of Skills Development and Placement.')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('css/course-slider.css') }}?v={{ filemtime(public_path('css/course-slider.css')) }}">
@endpush

@section('content')
        <section class="course-detail-hero">
            <div class="container course-detail-grid">
                <div class="course-detail-copy">
                    <p class="detail-kicker"><i data-lucide="{{ $course->icon }}"></i> {{ $course->title }}</p>
                    <h1>{{ $course->title }}</h1>
                    @include('partials.stars', ['rating' => $course->rating])
                    <p>{{ $course->short_description ?: $course->overview }}</p>
                </div>
                <div class="detail-carousel owl-carousel owl-theme">
                    @foreach ($gallery as $image)
                        <div class="detail-slide"><img src="{{ $image }}" data-fallback="{{ asset(\App\Models\CourseImage::FALLBACK) }}" alt="{{ $course->title }} training image {{ $loop->iteration }}"></div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="section detail-section">
            <div class="container detail-content-grid">
                <article class="detail-panel">
                    <h2>Course Details</h2>
                    @if ($course->details)
                        <div class="detail-rich ck-content">{!! $course->details !!}</div>
                    @endif
                    <ul class="detail-benefits">
                        <li><i data-lucide="circle-check"></i>Hands-on practical training</li>
                        <li><i data-lucide="circle-check"></i>Experienced instructors</li>
                        <li><i data-lucide="circle-check"></i>Recognized certification support</li>
                        <li><i data-lucide="circle-check"></i>Placement-focused preparation</li>
                    </ul>
                    <div class="detail-enroll">
                        <button class="button button-gold" type="button" data-open-enrollment data-course-id="{{ $course->id }}">Apply Now <i data-lucide="arrow-right"></i></button>
                    </div>
                </article>
            </div>
        </section>
        @if ($courses->isNotEmpty())
            <section class="section courses-section related-courses">
                <div class="container">
                    @include('partials.course-slider', ['sliderCourses' => $courses, 'sliderId' => 'related-courses-slider', 'sliderHeading' => 'Related Courses', 'sliderKicker' => 'KEEP EXPLORING'])
                </div>
            </section>
        @endif
@endsection

@push('vendor-scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/course-slider.js') }}?v={{ filemtime(public_path('js/course-slider.js')) }}"></script>
    <script>
        if (window.jQuery && jQuery.fn.owlCarousel) {
            const detailCount = jQuery('.detail-carousel .detail-slide').length;
            jQuery('.detail-carousel').owlCarousel({items: 1, loop: detailCount > 1, nav: detailCount > 1, dots: detailCount > 1, autoplay: detailCount > 1 && !matchMedia('(prefers-reduced-motion: reduce)').matches, autoplayTimeout: 3600, autoplayHoverPause: true});
        }
    </script>
@endpush
