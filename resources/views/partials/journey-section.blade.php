@php
    $steps = [
        ['icon' => 'search', 'title' => 'Choose your course', 'text' => 'Browse our programs and pick the skill that fits your career goals.'],
        ['icon' => 'file-pen-line', 'title' => 'Apply online', 'text' => 'Press "Enrolled now", select your course and share your contact details.'],
        ['icon' => 'phone-call', 'title' => 'Get confirmed', 'text' => 'Our admissions team calls you to confirm your seat and answer questions.'],
        ['icon' => 'graduation-cap', 'title' => 'Train and get placed', 'text' => 'Learn hands-on, earn your certificate and get placement support.'],
    ];
@endphp
<section class="section journey-section" id="journey" data-reveal aria-labelledby="journey-title">
    <div class="container">
        <div class="journey-head">
            <p class="section-kicker">YOUR PATH</p>
            <h2 id="journey-title">From first click to first job</h2>
            <p>Four simple steps to start your skills journey with Tech College.</p>
        </div>
        <ol class="journey-steps" data-stagger>
            @foreach ($steps as $step)
                <li class="journey-step">
                    <span class="journey-number" aria-hidden="true">{{ $loop->iteration }}</span>
                    <span class="journey-icon"><i data-lucide="{{ $step['icon'] }}"></i></span>
                    <h3>{{ $step['title'] }}</h3>
                    <p>{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
