<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'thumbnail',
        'trailer_url',
        'price',
        'sale_price',
        'currency',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'category_id',
        'created_by',
        'order',
    ];

    protected $appends = ['thumbnail_url'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'price', 'status', 'is_featured'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "La formation \"{$this->title}\" a été {$eventName}");
    }

    // Auto-generate slug
    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Module::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at', 'expires_at')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helpers
    public function getEffectivePrice(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getDuration(): int
    {
        return $this->lessons()->sum('duration_seconds');
    }

    public function getFormattedDuration(): string
    {
        $totalSeconds = $this->getDuration();
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}min";
        }
        return "{$minutes}min";
    }

    public function getStudentCount(): int
    {
        return $this->enrollments()->count();
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->thumbnail)) {
            return \Illuminate\Support\Facades\Storage::url($this->thumbnail);
        }
        return asset('images/course-placeholder.jpg');
    }
}
