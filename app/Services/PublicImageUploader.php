<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PublicImageUploader
{
    public function upload(
        UploadedFile $file,
        string $directory = 'images/uploads',
        int $qualityLevel = 2,
        ?int $maxWidth = null
    ): string {
        $publicDirectory = public_path(trim($directory, '/'));

        if (! File::exists($publicDirectory)) {
            File::makeDirectory($publicDirectory, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)
            ? $extension
            : 'jpg';

        $filename = Str::uuid() . '.' . ($extension === 'jpeg' ? 'jpg' : $extension);
        $fullPath = $publicDirectory . DIRECTORY_SEPARATOR . $filename;

        $quality = $this->resolveQuality($qualityLevel, $extension);
        $width = $maxWidth ?? $this->resolveMaxWidth($directory);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        if ($width && $image->width() > $width) {
            $image->scaleDown(width: $width);
        }

        if (in_array($extension, ['jpg', 'jpeg'], true)) {
            $image->toJpeg($quality)->save($fullPath);
        } elseif ($extension === 'png') {
            $image->toPng()->save($fullPath);
        } elseif ($extension === 'webp') {
            $image->toWebp($quality)->save($fullPath);
        } else {
            $image->toJpeg($quality)->save($fullPath);
        }

        return trim($directory, '/') . '/' . $filename;
    }

    public function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $fullPath = public_path(ltrim($relativePath, '/'));

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function resolveQuality(int $qualityLevel, string $extension): int
    {
        if ($extension === 'png') {
            return 100;
        }

        return match ($qualityLevel) {
            1 => 92,
            2 => 85,
            3 => 78,
            default => 85,
        };
    }

    private function resolveMaxWidth(string $directory): int
    {
        $directory = trim($directory, '/');

        return match (true) {
            str_contains($directory, 'images/sliders') => 1800,
            str_contains($directory, 'images/pages') => 1600,
            str_contains($directory, 'images/news/gallery') => 1600,
            str_contains($directory, 'images/news/blocks') => 1400,
            str_contains($directory, 'images/news') => 1600,
            default => 1600,
        };
    }
}