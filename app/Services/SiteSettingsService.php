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
        // Feature cards under the homepage hero
        'feature_1_title' => 'Career Counseling',
        'feature_1_text' => 'Guidance for your goals',
        'feature_2_title' => 'Recommendations',
        'feature_2_text' => 'The right course for you',
        'feature_3_title' => 'Course Completion',
        'feature_3_text' => 'Finish and get certified',
        'feature_4_title' => 'Profile Assessment',
        'feature_4_text' => 'Know your strengths',
        'feature_5_title' => 'Job Placement',
        'feature_5_text' => 'Support to get hired',
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

    /** Icon for each of the five feature card positions. */
    public const FEATURE_ICONS = ['messages-square', 'lightbulb', 'graduation-cap', 'clipboard-check', 'briefcase-business'];

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
            'map_url' => self::mapEmbedUrl($s['contact_map_url'], $s['contact_address']),
            'map_title' => self::mapQuery($s['contact_map_url'], $s['contact_address']),
            // The visitor's own Google Maps link when there is one, otherwise a search for the address.
            'map_link' => (self::isGoogleMapsLink($s['contact_map_url']) && ! self::validMapEmbed($s['contact_map_url']))
                ? trim($s['contact_map_url'])
                : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(preg_replace('/\s+/', ' ', (string) $s['contact_address'])),
            'map_directions' => 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode(self::directionsTarget($s['contact_map_url'], $s['contact_address'])),
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
            'features' => collect(range(1, 5))
                ->map(fn ($i) => [
                    'icon' => self::FEATURE_ICONS[$i - 1],
                    'title' => trim((string) $s['feature_' . $i . '_title']),
                    'text' => trim((string) $s['feature_' . $i . '_text']),
                ])
                ->filter(fn ($feature) => $feature['title'] !== '')
                ->values()
                ->all(),
            'hero' => [
                'kicker' => $s['hero_kicker'],
                'title' => $s['hero_title'],
                'text' => $s['hero_text'],
            ],
        ];
    }

    /**
     * Google only allows its "embed" addresses inside a page. A normal maps link (share link, place page,
     * maps.app.goo.gl) is refused and shows a grey box with a sad-face icon, so those are never rendered.
     */
    public static function validMapEmbed(?string $url): bool
    {
        $url = trim((string) $url);

        return $url !== '' && (bool) preg_match('#^https://(www\.google\.com/maps/embed(\?|/)|(www\.google\.com|maps\.google\.com)/maps\?[^\s]*output=embed)#i', $url);
    }

    /** Any Google Maps address: embed links, normal place/share links and the short maps.app.goo.gl links. */
    public static function isGoogleMapsLink(?string $url): bool
    {
        return (bool) preg_match('#^https://(www\.google\.com/maps|google\.com/maps|maps\.google\.com|maps\.app\.goo\.gl/|goo\.gl/maps)#i', trim((string) $url));
    }

    /**
     * Google's unique number for a business ("cid"), taken from the 0x...:0x... part of a place link.
     * Embedding by this number pins the exact place, unlike a name search which can land somewhere else.
     */
    public static function mapCid(?string $saved): ?string
    {
        if (! preg_match('/0x[0-9a-f]+:0x([0-9a-f]+)/i', (string) $saved, $m)) {
            return null;
        }

        $decimal = self::hexToDecimal($m[1]);

        return $decimal !== '0' ? $decimal : null;
    }

    /** Hex to decimal for numbers larger than PHP's integer range, without needing the bcmath/gmp extensions. */
    private static function hexToDecimal(string $hex): string
    {
        $digits = [0];

        foreach (str_split(strtolower($hex)) as $char) {
            $carry = hexdec($char);

            foreach ($digits as $i => $digit) {
                $value = $digit * 16 + $carry;
                $digits[$i] = $value % 10;
                $carry = intdiv($value, 10);
            }

            while ($carry > 0) {
                $digits[] = $carry % 10;
                $carry = intdiv($carry, 10);
            }
        }

        return ltrim(implode('', array_reverse($digits)), '0') ?: '0';
    }

    /**
     * What the saved Google Maps link points at, as a search text for Google: coordinates when the link has them,
     * otherwise the place name or search text inside it, otherwise the college address (short share links).
     */
    public static function mapQuery(?string $saved, ?string $address = null): string
    {
        $saved = trim((string) $saved);

        if ($saved !== '' && self::isGoogleMapsLink($saved) && ! self::validMapEmbed($saved)) {
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $saved, $m) || preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $saved, $m)) {
                return $m[1] . ',' . $m[2];
            }

            if (preg_match('/[?&](?:q|query)=([^&#]+)/', $saved, $m)) {
                return trim(urldecode($m[1]));
            }

            if (preg_match('#/maps/(?:place|search)/([^/@?]+)#', $saved, $m)) {
                return trim(urldecode(str_replace('+', ' ', $m[1])));
            }
        }

        return trim((string) preg_replace('/\s+/', ' ', (string) $address));
    }

    /**
     * Turns whatever Google Maps link the admin saved into an address that is allowed inside a page.
     * Embed links are used as they are; normal links are converted with mapQuery(). Returns '' when
     * there is nothing usable (no map is shown).
     */
    public static function mapEmbedUrl(?string $saved, ?string $address = null): string
    {
        $saved = trim((string) $saved);

        if ($saved === '' || ! self::isGoogleMapsLink($saved)) {
            return '';
        }

        if (self::validMapEmbed($saved)) {
            return $saved;
        }

        if ($cid = self::mapCid($saved)) {
            return 'https://maps.google.com/maps?cid=' . $cid . '&z=16&output=embed';
        }

        $query = self::mapQuery($saved, $address);

        return $query !== '' ? 'https://maps.google.com/maps?q=' . rawurlencode($query) . '&z=16&output=embed' : '';
    }
    /** Destination text for Google's directions page: coordinates as they are, a place name together with the address. */
    private static function directionsTarget(?string $saved, ?string $address): string
    {
        $address = trim((string) preg_replace('/\s+/', ' ', (string) $address));
        $query = self::mapQuery($saved, $address);

        if ($query === '' || $query === $address || preg_match('/^-?\d+\.\d+,-?\d+\.\d+$/', $query)) {
            return $query !== '' ? $query : $address;
        }

        return $address !== '' ? $query . ', ' . $address : $query;
    }

    /** Accepts the full <iframe ...> code Google gives out as well as the bare address inside it. */
    public static function extractMapSrc(?string $input): string
    {
        $input = trim((string) $input);

        if (stripos($input, '<iframe') !== false && preg_match('/src\s*=\s*(["\'])(.*?)\1/i', $input, $match)) {
            return html_entity_decode(trim($match[2]));
        }

        return $input;
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
