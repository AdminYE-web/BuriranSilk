<?php

namespace Tests\Feature;

use App\Models\AboutUsPage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('about_us_pages', function (Blueprint $table) {
            $table->id('about_us_page_id');
            $table->string('banner_desktop')->nullable();
            $table->string('banner_mobile')->nullable();
            $table->string('intro_image')->nullable();
            $table->longText('intro_content')->nullable();
            $table->longText('detail_content')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('about_us_pages');

        parent::tearDown();
    }

    public function test_about_company_page_remains_separate(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('about-page-wrapper', false);
        $response->assertDontSee('about-us-hero', false);
    }

    public function test_about_us_page_shows_hero_and_thai_silk_story(): void
    {
        $response = $this->get(route('about-us'));

        $response->assertOk();
        $response->assertSee('about-us-hero', false);
        $response->assertSee('about-us-story', false);
        $response->assertSee('about-silk-hero.jpg', false);
        $response->assertSee('about-silk-story.jpg', false);
        $response->assertSee('about-us-process', false);
        $response->assertSee('process-mulberry-tree.jpg', false);
        $response->assertSee('process-mulberry-leaves.jpg', false);
        $response->assertSee('process-silkworm-feeding.jpg', false);
        $response->assertSee('process-silkworm-cocoons.jpg', false);
    }

    public function test_about_us_page_uses_saved_cms_content(): void
    {
        AboutUsPage::create([
            'banner_desktop' => 'about-us/banner/desktop/banner.jpg',
            'banner_mobile' => 'about-us/banner/mobile/banner.jpg',
            'intro_image' => 'about-us/intro/intro.jpg',
            'intro_content' => '<h2>CMS Intro</h2><p>Editable introduction.</p>',
            'detail_content' => '<h2>CMS Detail</h2><figure class="media"><oembed url="https://youtu.be/testVideo123"></oembed></figure>',
        ]);

        $response = $this->get(route('about-us'));

        $response->assertOk();
        $response->assertSee('storage/about-us/banner/desktop/banner.jpg', false);
        $response->assertSee('storage/about-us/banner/mobile/banner.jpg', false);
        $response->assertSee('<h2>CMS Intro</h2>', false);
        $response->assertSee('<h2>CMS Detail</h2>', false);
        $response->assertSee('about-us-detail', false);
        $response->assertDontSee('about-us-process', false);
    }

    public function test_admin_can_update_about_us_cms_and_upload_editor_image(): void
    {
        Storage::fake('public');

        $response = $this->withoutMiddleware()->put(route('admin.about-us.update'), [
            'banner_desktop' => UploadedFile::fake()->image('desktop.jpg', 1920, 760),
            'banner_mobile' => UploadedFile::fake()->image('mobile.jpg', 750, 900),
            'intro_image' => UploadedFile::fake()->image('intro.jpg', 1200, 900),
            'intro_content' => '<h2>Managed intro</h2>',
            'detail_content' => '<p>Managed detail</p>',
        ]);

        $response->assertRedirect(route('admin.about-us.edit'));

        $page = AboutUsPage::query()->firstOrFail();
        $this->assertSame('<h2>Managed intro</h2>', $page->intro_content);
        $this->assertSame('<p>Managed detail</p>', $page->detail_content);
        Storage::disk('public')->assertExists($page->banner_desktop);
        Storage::disk('public')->assertExists($page->banner_mobile);
        Storage::disk('public')->assertExists($page->intro_image);

        $upload = $this->withoutMiddleware()->post(route('admin.about-us.upload-editor-image'), [
            'upload' => UploadedFile::fake()->image('editor.jpg', 900, 600),
        ], ['Accept' => 'application/json']);

        $upload->assertOk()->assertJsonStructure(['url']);
        Storage::disk('public')->assertExists('about-us/editor/'.basename($upload->json('url')));
    }
}
