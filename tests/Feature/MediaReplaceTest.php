<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaReplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_replace_overwrites_the_stored_file_and_keeps_the_public_path(): void
    {
        Storage::fake('public');

        $media = Media::create([
            'name' => 'Brochure',
            'file_name' => 'brochure.pdf',
            'path' => 'uploads/brochure.pdf',
            'disk' => 'public',
            'mime_type' => 'application/pdf',
            'size' => 3,
            'folder' => 'uploads',
        ]);
        Storage::disk('public')->put($media->path, 'old');

        $updated = app(MediaService::class)->replace(
            $media,
            UploadedFile::fake()->createWithContent('replacement.pdf', 'new-bytes'),
        );

        $this->assertSame($media->id, $updated->id);
        $this->assertSame('uploads/brochure.pdf', $updated->path);
        $this->assertSame('brochure.pdf', $updated->file_name);
        $this->assertSame('new-bytes', Storage::disk('public')->get('uploads/brochure.pdf'));
        $this->assertSame(1, Media::query()->count());
    }

    public function test_replace_keeps_the_path_when_the_stored_file_is_a_jpeg(): void
    {
        Storage::fake('public');

        $media = Media::create([
            'name' => 'Hero',
            'file_name' => 'hero.jpg',
            'path' => 'uploads/hero.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 3,
            'folder' => 'uploads',
        ]);
        Storage::disk('public')->put($media->path, 'old-image');

        $updated = app(MediaService::class)->replace(
            $media,
            UploadedFile::fake()->createWithContent('other.jpg', 'new-image'),
            true,
        );

        $this->assertSame($media->id, $updated->id);
        $this->assertSame('uploads/hero.jpg', $updated->path);
        $this->assertSame('new-image', Storage::disk('public')->get('uploads/hero.jpg'));
        $this->assertSame(1, Media::query()->count());
    }
}
