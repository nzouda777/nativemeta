<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Upload a file to storage.
     */
    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');

        return Storage::disk('public')->url($path);
    }

    /**
     * Upload a course thumbnail.
     */
    public function uploadThumbnail(UploadedFile $file): string
    {
        return $this->upload($file, 'courses/thumbnails');
    }

    /**
     * Upload a lesson content file (video, audio, PDF).
     */
    public function uploadLessonContent(UploadedFile $file, string $type): string
    {
        $directory = match ($type) {
            'video' => 'lessons/videos',
            'audio' => 'lessons/audio',
            'pdf' => 'lessons/pdf',
            default => 'lessons/other',
        };

        return $this->upload($file, $directory);
    }

    /**
     * Upload a user avatar.
     */
    public function uploadAvatar(UploadedFile $file): string
    {
        return $this->upload($file, 'avatars');
    }

    /**
     * Delete a file from storage.
     */
    public function delete(string $url): bool
    {
        $path = str_replace(Storage::disk('public')->url(''), '', $url);
        return Storage::disk('public')->delete($path);
    }

    /**
     * Get a temporary URL for private files (S3).
     */
    public function getTemporaryUrl(string $path, int $expirationMinutes = 60): string
    {
        if (config('filesystems.default') === 's3') {
            return Storage::temporaryUrl($path, now()->addMinutes($expirationMinutes));
        }

        return Storage::disk('public')->url($path);
    }
}
