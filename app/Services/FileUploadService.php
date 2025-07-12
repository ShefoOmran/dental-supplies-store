<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function uploadImage(UploadedFile $file, string $path = 'uploads'): string
    {
        // Validate file
        $this->validateFile($file);

        // Generate secure filename
        $filename = $this->generateSecureFilename($file);

        // Store file
        $filePath = $file->storeAs($path, $filename, 'public');

        // Optimize image
        $this->optimizeImage($filePath);

        return $filePath;
    }

    private function validateFile(UploadedFile $file): void
    {
        // Check file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('File size too large. Maximum size is 5MB.');
        }

        // Check MIME type
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \Exception('Invalid file type. Only images are allowed.');
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception('Invalid file extension.');
        }

        // Check for malicious content
        $this->scanForMaliciousContent($file);
    }

    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.' . $extension;
        
        return $filename;
    }

    private function scanForMaliciousContent(UploadedFile $file): void
    {
        $content = file_get_contents($file->getRealPath());
        
        // Check for PHP tags
        if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
            throw new \Exception('Malicious content detected.');
        }

        // Check for executable content
        $dangerousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload/i',
            '/onerror/i'
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new \Exception('Malicious content detected.');
            }
        }
    }

    private function optimizeImage(string $filePath): void
    {
        // Image optimization logic can be added here
        // For now, we'll just ensure the file is valid
        $fullPath = Storage::disk('public')->path($filePath);
        
        if (!getimagesize($fullPath)) {
            Storage::disk('public')->delete($filePath);
            throw new \Exception('Invalid image file.');
        }
    }

    public function deleteFile(string $filePath): bool
    {
        return Storage::disk('public')->delete($filePath);
    }
} 