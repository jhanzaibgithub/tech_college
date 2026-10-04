@php
    $faqs = [
        ['q' => 'How do I apply for a course?', 'a' => 'Press "Enrolled now" anywhere on the site, choose your course and submit your name and mobile number. Our team will contact you to confirm your admission.'],
        ['q' => 'Which mobile number should I enter?', 'a' => 'Use your 11-digit Pakistani mobile number starting with 03, for example 03001234567, so our admissions team can reach you.'],
        ['q' => 'Is placement support available?', 'a' => 'Yes. Our programs are job-focused and include career preparation and placement support for students.'],
        ['q' => 'Can I visit the college before enrolling?', 'a' => 'You are welcome to visit or call us' . ($site['hours'] ? ' (' . $site['hours'] . ')' : '') . '. Find our address and phone number on the Contact page.'],
    ];
@endphp
<section class="section faq-section" id="faq" data-reveal aria-labelledby="faq-title">
    <div class="container faq-grid">
        <div class="faq-intro">
            <p class="section-kicker">GOT QUESTIONS?</p>
            <h2 id="faq-title">Frequently asked questions</h2>
            <p>Can't find your answer? Our team is happy to help.</p>
            <a class="button" href="{{ route('contact') }}">Contact us <i data-lucide="arrow-right"></i></a>
        </div>
        <div class="faq-list">
            @foreach ($faqs as $faq)
                <details class="faq-item">
                    <summary>{{ $faq['q'] }}<i data-lucide="plus" aria-hidden="true"></i></summary>
                    <p>{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
