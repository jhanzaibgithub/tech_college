@extends('layouts.public')

@section('title', 'Contact Us | Tech College')
@section('description', 'Get in touch with Tech College admissions: phone, email, address and contact form.')

@section('content')
    @php($contactErrors = $errors->getBag('contact'))
    @include('partials.page-hero', ['page' => 'contact', 'kicker' => 'Contact Us'])
    <section class="section contact-section">
        <div class="container contact-grid" data-reveal>
            <div class="contact-info">
                <h2>Get in touch</h2>
                <ul class="contact-list">
                    <li><span><i data-lucide="map-pin"></i></span><div><strong>Address</strong><p>{!! nl2br(e($site['address'])) !!}</p></div></li>
                    <li><span><i data-lucide="phone"></i></span><div><strong>Phone</strong><p><a href="{{ $site['phone_href'] }}">{{ $site['phone'] }}</a></p></div></li>
                    <li><span><i data-lucide="mail"></i></span><div><strong>Email</strong><p><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></p></div></li>
                    @if ($site['hours'])
                        <li><span><i data-lucide="clock"></i></span><div><strong>Office hours</strong><p>{{ $site['hours'] }}</p></div></li>
                    @endif
                </ul>
            </div>
            <form class="contact-form" method="POST" action="{{ route('contact.store') }}" data-contact-form>
                @csrf
                <h2>Send a message</h2>
                @if ($contactErrors->any())
                    <div class="admin-error-list" role="alert">@foreach ($contactErrors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                @endif
                <div class="form-row">
                    <div><label for="contact-name">Full name</label><input id="contact-name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required></div>
                    <div><label for="contact-email">Email</label><input id="contact-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required></div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="contact-phone">Mobile <span>(optional)</span></label>
                        <input id="contact-phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" autocomplete="tel" maxlength="11" pattern="03[0-4][0-9]{8}" placeholder="03XXXXXXXXX" data-pk-phone>
                        <small class="field-error" data-phone-error hidden>Enter a valid Pakistani mobile number, Enter 11 digits, e.g. 03001234567.</small>
                    </div>
                    <div><label for="contact-subject">Subject <span>(optional)</span></label><input id="contact-subject" type="text" name="subject" value="{{ old('subject') }}" maxlength="255"></div>
                </div>
                <label for="contact-message">Message</label>
                <textarea id="contact-message" name="message" rows="5" maxlength="3000" required>{{ old('message') }}</textarea>
                <button class="button" type="submit">Send message <i data-lucide="send"></i></button>
            </form>
        </div>
        @if ($site['map_url'])
            <div class="container contact-map" data-reveal>
                <div class="map-heading">
                    <p class="section-kicker">FIND US</p>
                    <h2>Our location</h2>
                </div>
                <div class="map-frame">
                    <iframe src="{{ $site['map_url'] }}" title="Tech College location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    <div class="map-card">
                        <span class="map-pin" aria-hidden="true"><i data-lucide="map-pin"></i></span>
                        <div>
                            <strong>Tech College of Skills Development &amp; Placement</strong>
                            <p>{!! nl2br(e($site['address'])) !!}</p>
                            <div class="map-actions">
                                <a class="map-btn is-primary" href="{{ $site['map_directions'] }}" target="_blank" rel="noopener noreferrer"><i data-lucide="navigation"></i> Get directions</a>
                                <a class="map-btn" href="{{ $site['map_link'] }}" target="_blank" rel="noopener noreferrer"><i data-lucide="external-link"></i> Open in Google Maps</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
