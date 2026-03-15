<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class InvitationToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'token',
        'email',
        'order_id',
        'expires_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InvitationToken $token) {
            if (empty($token->token)) {
                $token->token = Str::random(64);
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->whereNull('used_at')
            ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // Helpers
    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}
