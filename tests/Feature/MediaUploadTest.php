<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
    }

    public function test_html_and_php_uploads_are_rejected_and_not_stored(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.media.store'), [
            'files' => [
                $this->uploaded('<html><script>alert(1)</script></html>', 'payload.html'),
            ],
        ])->assertSessionHasErrors('files.0');

        $this->actingAs($admin)->post(route('admin.media.store'), [
            'files' => [
                $this->uploaded('<?php echo "owned";', 'shell.php'),
            ],
        ])->assertSessionHasErrors('files.0');

        $this->assertSame(0, Media::count());
    }

    public function test_html_disguised_as_pdf_is_rejected_before_a_replace_deletes_the_original(): void
    {
        Storage::disk('public')->put('uploads/photo.jpg', 'original-bytes');

        $media = Media::create([
            'name' => 'photo',
            'file_name' => 'photo.jpg',
            'path' => 'uploads/photo.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 14,
            'folder' => 'uploads',
        ]);

        $this->actingAs($this->admin())->post(route('admin.media.replace', $media), [
            'file' => $this->uploaded('<html><script>alert(1)</script></html>', 'evil.pdf'),
        ])->assertSessionHasErrors('files');

        $this->assertNotNull(Media::find($media->id));
        Storage::disk('public')->assertExists('uploads/photo.jpg');
    }

    public function test_pdf_is_stored_with_a_pdf_extension_even_if_the_filename_contains_php(): void
    {
        $pdf = "%PDF-1.1\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n";

        $this->actingAs($this->admin())->post(route('admin.media.store'), [
            'files' => [
                $this->uploaded($pdf, 'brochure.php.pdf'),
            ],
        ])->assertSessionHasNoErrors();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertStringEndsWith('.pdf', $media->path);
        $this->assertStringNotContainsString('.php', $media->path);
        $this->assertSame('application/pdf', $media->mime_type);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_jpeg_upload_is_still_accepted(): void
    {
        $this->actingAs($this->admin())->post(route('admin.media.store'), [
            'files' => [
                UploadedFile::fake()->image('plotter.jpg', 20, 20),
            ],
        ])->assertSessionHasNoErrors();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertStringEndsWith('.webp', $media->path);
        $this->assertSame('image/webp', $media->mime_type);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_service_rejects_extensions_outside_the_allowlist(): void
    {
        $service = app(MediaService::class);

        $this->expectException(ValidationException::class);

        $service->guardedExtension($this->uploaded('<svg xmlns="http://www.w3.org/2000/svg"></svg>', 'logo.svg'));
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        return $user;
    }

    private function uploaded(string $contents, string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'media');
        file_put_contents($path, $contents);

        return new UploadedFile($path, $name, null, null, true);
    }
}
