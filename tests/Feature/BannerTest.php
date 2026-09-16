<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Banner;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $app = parent::createApplication();
        // These CRUD tests must never migrate or reset the site's database.
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('banners');
    }

    private function signIn(): void
    {
        $this->actingAs(Admin::create([
            'name' => 'Banner Editor', 'email' => 'banner-editor@example.test', 'password' => bcrypt('test-password'),
        ]), 'admin');
    }

    public function test_banner_management_requires_admin_authentication(): void
    {
        $banner = Banner::create(['title' => 'Existing', 'image_path' => 'existing.jpg', 'is_active' => true, 'sort_order' => 1]);
        $this->get(route('admin.banners.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.banners.create'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.banners.edit', $banner))->assertRedirect(route('admin.login'));
        $this->post(route('admin.banners.store'), [])->assertRedirect(route('admin.login'));
        $this->put(route('admin.banners.update', $banner), [])->assertRedirect(route('admin.login'));
        $this->delete(route('admin.banners.destroy', $banner))->assertRedirect(route('admin.login'));
        $this->assertDatabaseHas('banners', ['id' => $banner->id]);
    }

    public function test_admin_can_upload_edit_replace_and_delete_a_banner(): void
    {
        $this->signIn();
        $this->get(route('admin.banners.index'))->assertOk()->assertSee('Add Banner');
        $this->get(route('admin.banners.create'))->assertOk();
        $this->post(route('admin.banners.store'), [
            'title' => 'Admissions banner', 'image' => UploadedFile::fake()->image('banner.jpg', 1200, 400),
            'is_active' => '1', 'sort_order' => 3,
        ])->assertRedirect(route('admin.banners.index'))->assertSessionHasNoErrors();

        $banner = Banner::firstOrFail();
        $originalPath = $banner->image_path;
        Storage::disk('banners')->assertExists($originalPath);
        $this->get(route('admin.banners.edit', $banner))->assertOk()->assertSee('Admissions banner');
        $this->get('/')->assertOk()->assertSee($banner->imageUrl(), false);

        $this->put(route('admin.banners.update', $banner), [
            'title' => 'Hidden banner', 'is_active' => '0', 'sort_order' => 1,
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.banners.index'));
        $this->assertSame($originalPath, $banner->fresh()->image_path);
        $this->get('/')->assertDontSee($banner->imageUrl(), false);

        $this->put(route('admin.banners.update', $banner), [
            'title' => 'Replacement banner', 'image' => UploadedFile::fake()->image('replacement.png', 1200, 400),
            'is_active' => '1', 'sort_order' => 2,
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.banners.index'));
        $newPath = $banner->fresh()->image_path;
        $this->assertNotSame($originalPath, $newPath);
        Storage::disk('banners')->assertMissing($originalPath);
        Storage::disk('banners')->assertExists($newPath);

        $this->delete(route('admin.banners.destroy', $banner))->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
        Storage::disk('banners')->assertMissing($newPath);
    }

    public function test_upload_validation_rejects_missing_non_image_and_oversized_files(): void
    {
        $this->signIn();
        $fields = ['title' => 'Invalid banner', 'is_active' => '1', 'sort_order' => 0];
        $this->post(route('admin.banners.store'), $fields)->assertSessionHasErrors('image');
        $this->post(route('admin.banners.store'), $fields + [
            'image' => UploadedFile::fake()->create('script.svg', 10, 'image/svg+xml'),
        ])->assertSessionHasErrors('image');
        $this->post(route('admin.banners.store'), $fields + [
            'image' => UploadedFile::fake()->image('large.jpg')->size(8193),
        ])->assertSessionHasErrors('image');
        $this->post(route('admin.banners.store'), array_replace($fields, [
            'sort_order' => -1, 'image' => UploadedFile::fake()->image('banner.jpg'),
        ]))->assertSessionHasErrors('sort_order');
        $this->assertDatabaseCount('banners', 0);
        $this->assertSame([], Storage::disk('banners')->allFiles());
    }

    public function test_homepage_shows_only_active_banners_in_display_order(): void
    {
        foreach ([['Later', 5, true], ['Hidden', 0, false], ['First', 1, true]] as [$title, $order, $active]) {
            Banner::create(['title' => $title, 'image_path' => strtolower($title).'.jpg', 'sort_order' => $order, 'is_active' => $active]);
        }
        $this->get('/')->assertOk()
            ->assertSeeInOrder(['data/banners/first.jpg', 'data/banners/later.jpg'], false)
            ->assertDontSee('data/banners/hidden.jpg', false)
            ->assertSee('Next banner')
            ->assertDontSee('Pause slideshow')
            ->assertSee('Explore Courses')->assertSee('Admissions Open')
            ->assertDontSee('Build your skills')->assertDontSee('empowers youth with practical training');
    }

    public function test_empty_and_single_banner_states_keep_actions_without_carousel_controls(): void
    {
        $this->get('/')->assertOk()->assertSee('data/hero-students-placeholder.png', false)
            ->assertSee('Explore Courses')->assertSee('Admissions Open')->assertDontSee('Next banner');
        Banner::create(['title' => 'Only banner', 'image_path' => 'only.jpg', 'is_active' => true, 'sort_order' => 1]);
        $this->get('/')->assertOk()->assertSee('data/banners/only.jpg', false)
            ->assertDontSee('data/hero-students-placeholder.png', false)->assertDontSee('Next banner');
    }
}
