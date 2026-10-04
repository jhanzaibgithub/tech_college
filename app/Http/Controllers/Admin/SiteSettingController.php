<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    private const SECTIONS = [
        'contact' => ['title' => 'Contact & Social Links', 'blurb' => 'Shown on the contact page, top bar and footer.'],
        'stats' => ['title' => 'Statistics', 'blurb' => 'Numbers shown in the animated counters on the homepage.'],
        'about' => ['title' => 'About Us', 'blurb' => 'Content for the About section and About page.'],
        'pages' => ['title' => 'Page Heroes', 'blurb' => 'Banner image, title and text at the top of the About, Courses and Contact pages.'],
        'hero' => ['title' => 'Homepage Hero', 'blurb' => 'Headline text shown over the homepage banners.'],
    ];

    public function __construct(private readonly SiteSettingsService $settings)
    {
    }

    public function edit(string $section): View
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);

        return view('admin.settings.' . $section, [
            'section' => self::SECTIONS[$section],
            'values' => $this->settings->all(),
        ]);
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);

        $data = $request->validate($this->rules($section));

        foreach (['about_image', 'page_about_image', 'page_courses_image', 'page_contact_image'] as $imageField) {
            unset($data[$imageField]);
            if ($request->hasFile($imageField)) {
                $data[$imageField] = $this->storeImage($request, $imageField);
            }
        }

        $this->settings->update($data);

        return redirect()->route('admin.settings.edit', $section)->with('status', self::SECTIONS[$section]['title'] . ' updated.');
    }

    private function rules(string $section): array
    {
        $url = ['nullable', 'url:http,https', 'max:500'];
        $count = ['integer', 'min:0', 'max:99999999'];

        return match ($section) {
            'contact' => [
                'contact_email' => ['required', 'email', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:40'],
                'contact_address' => ['required', 'string', 'max:500'],
                'contact_hours' => ['nullable', 'string', 'max:255'],
                'contact_map_url' => $url,
                'social_whatsapp' => ['nullable', 'string', 'max:500', 'regex:/^(https?:\/\/\S+|[+\d][\d\s\-]{6,19})$/'],
                'social_facebook' => $url,
                'social_youtube' => $url,
                'social_tiktok' => $url,
                'social_instagram' => $url,
            ],
            'stats' => [
                'stat_students' => ['required', ...$count],
                'stat_completed' => ['required', ...$count],
                'stat_courses' => ['nullable', ...$count],
                'stat_years' => ['required', 'integer', 'min:0', 'max:999'],
            ],
            'about' => [
                'about_title' => ['required', 'string', 'max:255'],
                'about_tagline' => ['nullable', 'string', 'max:255'],
                'about_description' => ['required', 'string', 'max:3000'],
                'about_points' => ['nullable', 'string', 'max:2000'],
                'about_mission' => ['nullable', 'string', 'max:2000'],
                'about_vision' => ['nullable', 'string', 'max:2000'],
                'about_institution' => ['nullable', 'string', 'max:3000'],
                'about_quote' => ['nullable', 'string', 'max:255'],
                'about_image' => ['nullable', 'image', 'max:4096'],
            ],
            'pages' => collect(['about', 'courses', 'contact'])->flatMap(fn ($page) => [
                'page_' . $page . '_title' => ['required', 'string', 'max:160'],
                'page_' . $page . '_text' => ['nullable', 'string', 'max:300'],
                'page_' . $page . '_image' => ['nullable', 'image', 'max:4096'],
            ])->all(),
            'hero' => [
                'hero_kicker' => ['nullable', 'string', 'max:120'],
                'hero_title' => ['required', 'string', 'max:160'],
                'hero_text' => ['nullable', 'string', 'max:400'],
            ],
        };
    }

    private function storeImage(Request $request, string $field): string
    {
        $file = $request->file($field);
        $directory = public_path('data/pages');
        File::ensureDirectoryExists($directory);

        $filename = Str::slug(str_replace('_', '-', $field)) . '-' . Str::random(10) . '.' . $file->guessExtension();
        $file->move($directory, $filename);

        return 'data/pages/' . $filename;
    }
}
