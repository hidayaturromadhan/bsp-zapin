<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PublicImageUploader
{
    /**
     * Upload gambar ke folder public, resize, compress, dan convert otomatis ke WEBP.
     *
     * Parameter tetap dibuat kompatibel dengan controller lama:
     * upload($file, $directory, $qualityLevel, $maxWidth)
     */
    public function upload(
        UploadedFile $file,
        string $directory = 'images/uploads',
        int $qualityLevel = 2,
        ?int $maxWidth = null
    ): string {
        $this->validateImageFile($file);

        $directory = trim($directory, '/');
        $publicDirectory = public_path($directory);

        if (! File::exists($publicDirectory)) {
            File::makeDirectory($publicDirectory, 0755, true);
        }

        /*
         * Semua gambar disimpan sebagai WEBP.
         * Walaupun user upload jpg/png/webp, output tetap .webp.
         */
        $filename = Str::uuid() . '.webp';
        $fullPath = $publicDirectory . DIRECTORY_SEPARATOR . $filename;

        $quality = $this->resolveQuality($qualityLevel);
        $width = $maxWidth ?? $this->resolveMaxWidth($directory);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        /*
         * Resize hanya kalau gambar lebih besar dari batas.
         * Tidak memperbesar gambar kecil.
         */
        if ($width && $image->width() > $width) {
            $image->scaleDown(width: $width);
        }

        /*
         * Convert dan compress ke WEBP.
         */
        $image->toWebp($quality)->save($fullPath);

        return $directory . '/' . $filename;
    }

    /**
     * Hapus gambar lama dari public folder.
     */
    public function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $relativePath = ltrim($relativePath, '/');

        /*
         * Pengaman agar tidak bisa menghapus path mencurigakan.
         */
        if (
            str_contains($relativePath, '..') ||
            str_starts_with($relativePath, '/') ||
            str_starts_with($relativePath, '\\')
        ) {
            return;
        }

        $fullPath = public_path($relativePath);

        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }

    /**
     * Validasi tambahan di level service.
     * Validasi utama tetap sebaiknya ada di controller/form request.
     */
    private function validateImageFile(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            abort(422, 'File gambar tidak valid atau gagal diupload.');
        }

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (! in_array($file->getMimeType(), $allowedMimes, true)) {
            abort(422, 'Format gambar harus JPG, JPEG, PNG, atau WEBP.');
        }
    }

    /**
     * Level kualitas:
     * 1 = kualitas tinggi, ukuran lebih besar
     * 2 = standar aman
     * 3 = lebih kecil untuk kompres kuat
     */
    private function resolveQuality(int $qualityLevel): int
    {
        return match ($qualityLevel) {
            1 => 88,
            2 => 80,
            3 => 72,
            default => 80,
        };
    }

    /**
     * Batas lebar gambar berdasarkan folder.
     */
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