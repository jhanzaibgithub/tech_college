<?php

namespace App\Services;

use App\Models\Course;
use App\Models\SiteSetting;
use Illuminate\Database\QueryException;

/**
 * Admin-editable site content (contact details, social links, statistics, about text, hero copy).
 * Stored as key/value rows; anything not yet saved falls back to DEFAULTS.
 */
class SiteSettingsService
{
    public const DEFAULTS = [
        // Contact
        'contact_email' => 'techcollegepak@gmail.com',
        'contact_phone' => '051-4627600',
        'contact_address' => "Hakim Khan Plaza, Main GT Road, Rawat,\nRawalpindi, Pakistan",
        'contact_hours' => 'Mon - Sat, 9:00 AM - 5:00 PM',
        'contact_map_url' => '',
        'social_whatsapp' => 'https://www.whatsapp.com/',
        'social_facebook' => 'https://www.facebook.com/',
        'social_youtube' => 'https://www.youtube.com/',
        'social_tiktok' => 'https://www.tiktok.com/',
        'social_instagram' => 'https://www.instagram.com/',
        // Statistics
        'stat_students' => '1500',
        'stat_completed' => '1000',
        'stat_courses' => '',
        'stat_years' => '10',
        // About
        'about_title' => 'Why Choose Tech College?',
        'about_tagline' => 'Your Skills. Our Mission.',
        'about_description' => 'Tech College of Skills Development & Placement empowers youth with practical skills, knowledge and opportunities to build a better future.',
        'about_points' => "Hands-on, practical and industry-focused training\nExperienced and qualified trainers\n100% placement support\nRecognized certification\nCareer-oriented learning environment",
        'about_mission' => 'To equip students with job-ready technical and professional skills through hands-on training and career support.',
        'about_vision' => 'To be a trusted centre of skills development that turns motivated students into confident professionals.',
        'about_institution' => '',
        'about_image' => 'data/campus-building.png',
        'about_quote' => 'Skills create opportunities.',
        // Inner page heroes
        'page_about_title' => 'About Tech College',
        'page_about_text' => 'Skills, training and placement support for a better future.',
        'page_about_image' => 'data/banners/student-learning.png',
        'page_courses_title' => 'Courses built for careers',
        'page_courses_text' => 'Practical, job-focused programs with hands-on training and placement support.',
        'page_courses_image' => 'data/banners/technical-training.png',
        'page_contact_title' => 'We would love to hear from you',
        'page_contact_text' => 'Questions about admissions or courses? Send us a message or visit us.',
        'page_contact_image' => 'data/banners/career-preparation.png',
        // Homepage hero
        'hero_kicker' => 'Skills Development & Placement',
        'hero_title' => 'Build job-ready skills for a career that lasts',
        'hero_text' => 'Practical technical and professional training, recognised certification and placement support for students across Pakistan.',
    ];

    public const SOCIAL = ['whatsapp', 'facebook', 'youtube', 'tiktok', 'instagram'];

    private ?array $cache = null;

    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        try {
            $stored = SiteSetting::query()->pluck('value', 'key')->all();
        } catch (QueryException) {
            $stored = []; // table not migrated yet
        }

        $settings = self::DEFAULTS;
        foreach ($stored as $key => $value) {
            // An empty string is kept: it means the admin deliberately cleared the value.
            if (array_key_exists($key, $settings) && $value !== null) {
                $settings[$key] = $value;
            }
        }

        return $this->cache = $settings;
    }

    public function get(string $key): ?string
    {
        return $this->all()[$key] ?? null;
    }

    public function update(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::DEFAULTS)) {
                continue;
            }
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value === null ? '' : (string) $value]);
        }

        $this->cache = null;
    }

    /** Settings prepared for public views. */
    public function site(): array
    {
        $s = $this->all();

        $social = [];
        foreach (self::SOCIAL as $network) {
            $url = $this->socialUrl($network, $s['social_' . $network]);
            if ($url) {
                $social[$network] = $network === 'whatsapp' ? $this->withWhatsappGreeting($url) : $url;
            }
        }

        $courseCount = $s['stat_courses'] !== '' ? (int) $s['stat_courses'] : $this->activeCourseCount();

        return [
            'email' => $s['contact_email'],
            'phone' => $s['contact_phone'],
            'phone_href' => 'tel:' . preg_replace('/[^\d+]/', '', $s['contact_phone']),
            'address' => $s['contact_address'],
            'hours' => $s['contact_hours'],
            'map_url' => $s['contact_map_url'],
            'social' => $social,
            'stats' => [
                ['value' => (int) $s['stat_students'], 'label' => 'Students Enrolled', 'icon' => 'users'],
                ['value' => $courseCount, 'label' => 'Courses', 'icon' => 'book-open'],
                ['value' => (int) $s['stat_completed'], 'label' => 'Completed Students', 'icon' => 'graduation-cap'],
                ['value' => (int) $s['stat_years'], 'label' => 'Years of Experience', 'icon' => 'award'],
            ],
            'about' => [
                'title' => $s['about_title'],
                'tagline' => $s['about_tagline'],
                'description' => $s['about_description'],
                'points' => array_values(array_filter(array_map('trim', preg_split('/\R/', $s['about_points'])))),
                'mission' => $s['about_mission'],
                'vision' => $s['about_vision'],
                'institution' => $s['about_institution'],
                'image' => $this->existingAsset($s['about_image']),
                'quote' => $s['about_quote'],
            ],
            'pages' => collect(['about', 'courses', 'contact'])->mapWithKeys(fn ($page) => [$page => [
                'title' => $s['page_' . $page . '_title'],
                'text' => $s['page_' . $page . '_text'],
                'image' => $this->existingAsset($s['page_' . $page . '_image']),
            ]])->all(),
            'hero' => [
                'kicker' => $s['hero_kicker'],
                'title' => $s['hero_title'],
                'text' => $s['hero_text'],
            ],
        ];
    }

    private function activeCourseCount(): int
    {
        try {
            return Course::where('is_active', true)->count();
        } catch (QueryException) {
            return 0;
        }
    }

    private function existingAsset(?string $path): string
    {
        $path = $path ?: 'data/campus-building.png';

        return asset(is_file(public_path($path)) ? $path : 'data/campus-building.png');
    }

    /** wa.me chat links open with a ready-made first message. */
    private function withWhatsappGreeting(string $url): string
    {
        if (! str_contains($url, 'wa.me/') || str_contains($url, '?')) {
            return $url;
        }

        return $url . '?text=' . rawurlencode('Hello Tech College, I would like to know about admissions.');
    }

    private function socialUrl(string $network, ?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if ($network === 'whatsapp' && ! str_starts_with($value, 'http')) {
            $digits = preg_replace('/\D/', '', $value);
            if (str_starts_with($digits, '0')) {
                $digits = '92' . substr($digits, 1);
            }

            return $digits ? 'https://wa.me/' . $digits : null;
        }

        return preg_match('#^https?://#i', $value) ? $value : null;
    }
}
