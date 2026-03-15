<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'type',
        'content_url',
        'content_text',
        'duration_seconds',
        'order',
        'is_preview',
    ];

    protected function casts(): array
    {
        return [
            'is_preview' => 'boolean',
            'duration_seconds' => 'integer',
        ];
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Module::class,
            'id',        // modules.id
            'id',        // courses.id
            'module_id', // lessons.module_id
            'course_id'  // modules.course_id
        );
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function userProgress($userId)
    {
        return $this->progress()->where('user_id', $userId)->first();
    }

    public function getFormattedDuration(): string
    {
        if (!$this->duration_seconds) return '';

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }

    public function isPdf(): bool
    {
        return $this->type === 'pdf';
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }
}
