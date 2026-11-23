<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    protected array $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/x-ms-bmp'];

    /** Extensions written to the public disk. Active content types are rejected. */
    public const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp',
        'pdf', 'zip', 'xlsx', 'xls', 'doc', 'docx',
        'mp4', 'webm', 'mov',
    ];

    protected array $blockedMimes = [
        'text/html',
        'application/xhtml+xml',
        'image/svg+xml',
        'application/javascript',
        'text/javascript',
        'application/x-httpd-php',
        'application/x-php',
        'text/x-php',
        'application/php',
    ];

    public function upload(UploadedFile $file, ?string $folder = null, bool $convertWebp = true, ?string $altText = null): Media
    {
        $folder = $this->safeFolder($folder);
        $extension = $this->guardedExtension($file);
        $originalName = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'-'.Str::random(6);

        $isImage = in_array($mime, $this->imageMimes, true);

        if ($isImage && $convertWebp && $mime !== 'image/webp' && $mime !== 'image/gif') {
            $fileName = $baseName.'.webp';
            $path = $folder.'/'.$fileName;
            $image = Image::decodePath($file->getRealPath());
            $encoded = $image->encodeUsingMediaType('image/webp', quality: 82);
            Storage::disk('public')->put($path, (string) $encoded);
            $size = Storage::disk('public')->size($path);
            $mime = 'image/webp';
        } else {
            $fileName = $baseName.'.'.$extension;
            $path = $folder.'/'.$fileName;
            Storage::disk('public')->putFileAs($folder, $file, $fileName);
            $size = $file->getSize();
        }

        return Media::create([
            'user_id' => auth()->id(),
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $fileName,
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $mime,
            'size' => $size,
            'folder' => $folder,
            'alt_text' => $altText,
        ]);
    }

    public function uploadMany(array $files, ?string $folder = null, bool $convertWebp = true): array
    {
        $media = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $media[] = $this->upload($file, $folder, $convertWebp);
            }
        }

        return $media;
    }

    public function delete(Media $media): bool
    {
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        return (bool) $media->delete();
    }

    public function replace(Media $media, UploadedFile $file, bool $convertWebp = true): Media
    {
        // Reject a disallowed replacement before the original file is removed.
        $this->guardedExtension($file);

        $folder = $media->folder;
        $altText = $media->alt_text;
        $this->delete($media);

        return $this->upload($file, $folder, $convertWebp, $altText);
    }

    public function folders(): array
    {
        return Media::query()
            ->select('folder')
            ->whereNotNull('folder')
            ->distinct()
            ->orderBy('folder')
            ->pluck('folder')
            ->toArray();
    }

    public function createFolder(string $folder): void
    {
        Storage::disk('public')->makeDirectory($this->safeFolder($folder));
    }

    /**
     * Keep the client extension only when it is on the public-disk allowlist
     * and the detected type is not HTML, SVG, or PHP. The stored name never
     * reuses a dangerous extension such as .php or .html.
     */
    public function guardedExtension(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = strtolower((string) $file->getMimeType());

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true) || $this->mimeIsBlocked($mime)) {
            throw ValidationException::withMessages([
                'files' => 'This file type is not allowed. Upload an image, PDF, ZIP, Office document, or video.',
            ]);
        }

        return $extension === 'jpeg' ? 'jpg' : $extension;
    }

    protected function mimeIsBlocked(string $mime): bool
    {
        return $mime === ''
            || in_array($mime, $this->blockedMimes, true)
            || str_contains($mime, 'php')
            || str_contains($mime, 'html')
            || str_contains($mime, 'javascript')
            || str_contains($mime, 'svg');
    }

    protected function safeFolder(?string $folder): string
    {
        $folder = trim(str_replace(['..', '\\'], '', (string) ($folder ?: 'uploads')), '/');

        return $folder !== '' ? $folder : 'uploads';
    }

    public function paginate(array $filters = [], int $perPage = 24)
    {
        $query = Media::query()->latest();

        if (! empty($filters['folder'])) {
            $query->where('folder', $filters['folder']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            match ($filters['type']) {
                'image' => $query->where('mime_type', 'like', 'image/%'),
                'video' => $query->where('mime_type', 'like', 'video/%'),
                'pdf' => $query->where('mime_type', 'application/pdf'),
                'document' => $query->where(function ($q) {
                    $q->where('mime_type', 'like', 'application/%')
                        ->orWhere('mime_type', 'like', 'text/%');
                }),
                default => null,
            };
        }

        return $query->paginate($perPage);
    }
}
