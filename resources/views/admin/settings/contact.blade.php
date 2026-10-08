@extends('admin.layout')

@section('title', $section['title'])

@section('content')
    <form class="admin-form-grid" method="POST" action="{{ route('admin.settings.update', 'contact') }}">
        @csrf @method('PUT')
        <section class="admin-card">
            <div class="admin-card-head"><div><h2>{{ $section['title'] }}</h2><p>{{ $section['blurb'] }}</p></div></div>
            @include('admin.settings._errors')
            <h3>Contact details</h3>
            <div class="admin-fields">
                <label>Email <input type="email" name="contact_email" value="{{ old('contact_email', $values['contact_email']) }}" required></label>
                <label>Phone number <input type="text" name="contact_phone" value="{{ old('contact_phone', $values['contact_phone']) }}" required></label>
            </div>
            <div class="admin-full"><label>Address <textarea name="contact_address" rows="3" required>{{ old('contact_address', $values['contact_address']) }}</textarea></label></div>
            <div class="admin-fields">
                <label>Office hours <input type="text" name="contact_hours" value="{{ old('contact_hours', $values['contact_hours']) }}" placeholder="Mon - Sat, 9:00 AM - 5:00 PM"></label>
                <label>Google Maps embed <textarea name="contact_map_url" rows="3" placeholder="Paste your Google Maps link or the embed code. Leave empty for no map.">{{ old('contact_map_url', $values['contact_map_url']) }}</textarea>
                    @if (filled($values['contact_map_url']) && ! \App\Services\SiteSettingsService::isGoogleMapsLink($values['contact_map_url']))
                        <small class="field-error" style="display:block">The saved text is not a Google Maps link, so no map is shown on the website. Paste a Google Maps link or embed code, or clear this field.</small>
                    @else
                        <small class="field-hint">Optional. Any Google Maps link works (place link, short share link or embed code). If empty, the Contact page shows no map.</small>
                    @endif
                </label>
            </div>
            <h3>Social media</h3>
            <p class="field-hint">Only platforms with a link are shown on the website.</p>
            <div class="admin-fields">
                <label>WhatsApp (number or link) <input type="text" name="social_whatsapp" value="{{ old('social_whatsapp', $values['social_whatsapp']) }}" placeholder="03001234567 or https://wa.me/923001234567"></label>
                <label>Facebook <input type="url" name="social_facebook" value="{{ old('social_facebook', $values['social_facebook']) }}" placeholder="https://facebook.com/yourpage"></label>
                <label>YouTube <input type="url" name="social_youtube" value="{{ old('social_youtube', $values['social_youtube']) }}" placeholder="https://youtube.com/@yourchannel"></label>
                <label>TikTok <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $values['social_tiktok']) }}" placeholder="https://tiktok.com/@youraccount"></label>
                <label>Instagram <input type="url" name="social_instagram" value="{{ old('social_instagram', $values['social_instagram']) }}" placeholder="https://instagram.com/youraccount"></label>
            </div>
        </section>
        <div class="admin-form-actions"><button class="admin-button" type="submit"><i data-lucide="save"></i> Save changes</button></div>
    </form>
@endsection
