<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class ImageUploadService
{
    public function storeConverted(
        UploadedFile $file,
        string $directory,
        ?int $width = null,
        ?int $height = null,
        ?string $oldPath = null,
        string $format = 'webp'
    ): string {
        $format = $this->normalizeFormat($format);
        $targetDirectory = public_path($directory);

        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        if ($oldPath && File::exists(public_path($oldPath))) {
            File::delete(public_path($oldPath));
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $baseName = $baseName !== '' ? $baseName : 'image';
        $fileName = $this->availableFileName($targetDirectory, $baseName, $format);
        $targetPath = $targetDirectory.DIRECTORY_SEPARATOR.$fileName;

        [$source, $sourceWidth, $sourceHeight] = $this->createSourceImage($file);
        [$targetWidth, $targetHeight] = $this->targetSize($sourceWidth, $sourceHeight, $width, $height);

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        $this->prepareCanvas($canvas, $format);

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight
        );

        if (! $this->saveImage($canvas, $targetPath, $format)) {
            imagedestroy($source);
            imagedestroy($canvas);

            throw new RuntimeException('Unable to save optimized image.');
        }

        imagedestroy($source);
        imagedestroy($canvas);

        return trim($directory, '/').'/'.$fileName;
    }

    public function storeAsWebp(
        UploadedFile $file,
        string $directory,
        ?int $width = null,
        ?int $height = null,
        ?string $oldPath = null
    ): string {
        return $this->storeConverted($file, $directory, $width, $height, $oldPath, 'webp');
    }

    private function createSourceImage(UploadedFile $file): array
    {
        $contents = file_get_contents($file->getRealPath());
        $source = imagecreatefromstring($contents);

        if (! $source) {
            throw new RuntimeException('Unsupported image file.');
        }

        return [$source, imagesx($source), imagesy($source)];
    }

    private function targetSize(int $sourceWidth, int $sourceHeight, ?int $width, ?int $height): array
    {
        if ($width && $height) {
            return [$width, $height];
        }

        if ($width) {
            return [$width, (int) round($sourceHeight * ($width / $sourceWidth))];
        }

        if ($height) {
            return [(int) round($sourceWidth * ($height / $sourceHeight)), $height];
        }

        return [$sourceWidth, $sourceHeight];
    }

    private function normalizeFormat(string $format): string
    {
        $format = strtolower($format);

        return in_array($format, ['jpg', 'png', 'webp'], true) ? $format : 'webp';
    }

    private function prepareCanvas($canvas, string $format): void
    {
        if ($format === 'jpg') {
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $white);

            return;
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
    }

    private function saveImage($canvas, string $targetPath, string $format): bool
    {
        return match ($format) {
            'jpg' => imagejpeg($canvas, $targetPath, 86),
            'png' => imagepng($canvas, $targetPath, 6),
            default => imagewebp($canvas, $targetPath, 82),
        };
    }

    private function availableFileName(string $directory, string $baseName, string $extension): string
    {
        $fileName = $baseName.'.'.$extension;
        $counter = 2;

        while (File::exists($directory.DIRECTORY_SEPARATOR.$fileName)) {
            $fileName = $baseName.'-'.$counter.'.'.$extension;
            $counter++;
        }

        return $fileName;
    }
}
