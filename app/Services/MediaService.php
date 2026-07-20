<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MediaService
{
    protected array $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];

    public function upload(UploadedFile $file, ?string $folder = null, bool $convertWebp = true, ?string $altText = null): Media
    {
        $folder = $folder ?: 'uploads';
        $originalName = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
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
            $path = $file->storeAs($folder, $fileName, 'public');
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
        $folder = trim(str_replace(['..', '\\'], '', $folder), '/');
        Storage::disk('public')->makeDirectory($folder);
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
