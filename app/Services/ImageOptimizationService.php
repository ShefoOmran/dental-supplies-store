<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageOptimizationService
{
    private array $sizes = [
        'thumbnail' => [150, 150],
        'medium' => [300, 300],
        'large' => [800, 800]
    ];

    public function optimizeImage(string $filePath, string $disk = 'public'): array
    {
        $fullPath = Storage::disk($disk)->path($filePath);
        $pathInfo = pathinfo($filePath);
        $optimizedImages = [];

        // Create optimized versions
        foreach ($this->sizes as $size => $dimensions) {
            $optimizedPath = $this->createOptimizedVersion(
                $fullPath,
                $pathInfo,
                $size,
                $dimensions,
                $disk
            );
            $optimizedImages[$size] = $optimizedPath;
        }

        return $optimizedImages;
    }

    private function createOptimizedVersion(
        string $originalPath,
        array $pathInfo,
        string $size,
        array $dimensions,
        string $disk
    ): string {
        $image = Image::make($originalPath);
        
        // Resize image maintaining aspect ratio
        $image->resize($dimensions[0], $dimensions[1], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Optimize quality
        $image->encode('jpg', 85);

        // Generate new filename
        $newFilename = $pathInfo['filename'] . "_{$size}." . $pathInfo['extension'];
        $newPath = $pathInfo['dirname'] . '/' . $newFilename;

        // Save optimized image
        Storage::disk($disk)->put($newPath, $image->stream());

        return $newPath;
    }

    public function generateWebP(string $filePath, string $disk = 'public'): string
    {
        $fullPath = Storage::disk($disk)->path($filePath);
        $pathInfo = pathinfo($filePath);

        $image = Image::make($fullPath);
        $image->encode('webp', 85);

        $webpFilename = $pathInfo['filename'] . '.webp';
        $webpPath = $pathInfo['dirname'] . '/' . $webpFilename;

        Storage::disk($disk)->put($webpPath, $image->stream());

        return $webpPath;
    }

    public function createResponsiveImages(string $filePath, string $disk = 'public'): array
    {
        $fullPath = Storage::disk($disk)->path($filePath);
        $pathInfo = pathinfo($filePath);
        $responsiveImages = [];

        // Create different sizes for responsive design
        $sizes = [
            'xs' => [320, 320],
            'sm' => [576, 576],
            'md' => [768, 768],
            'lg' => [992, 992],
            'xl' => [1200, 1200]
        ];

        foreach ($sizes as $breakpoint => $dimensions) {
            $image = Image::make($fullPath);
            $image->resize($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $image->encode('webp', 85);

            $responsiveFilename = $pathInfo['filename'] . "_{$breakpoint}.webp";
            $responsivePath = $pathInfo['dirname'] . '/' . $responsiveFilename;

            Storage::disk($disk)->put($responsivePath, $image->stream());
            $responsiveImages[$breakpoint] = $responsivePath;
        }

        return $responsiveImages;
    }

    public function deleteOptimizedImages(string $filePath, string $disk = 'public'): void
    {
        $pathInfo = pathinfo($filePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        // Delete all optimized versions
        foreach ($this->sizes as $size => $dimensions) {
            $optimizedPath = $directory . '/' . $filename . "_{$size}." . $pathInfo['extension'];
            Storage::disk($disk)->delete($optimizedPath);
        }

        // Delete WebP versions
        $webpPath = $directory . '/' . $filename . '.webp';
        Storage::disk($disk)->delete($webpPath);

        // Delete responsive versions
        $breakpoints = ['xs', 'sm', 'md', 'lg', 'xl'];
        foreach ($breakpoints as $breakpoint) {
            $responsivePath = $directory . '/' . $filename . "_{$breakpoint}.webp";
            Storage::disk($disk)->delete($responsivePath);
        }
    }
} 