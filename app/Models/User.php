<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /** Admin? */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** User biasa? */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Cek apakah data profil pengguna sudah lengkap (nama, email, nomor telepon, dan alamat).
     */
    public function isProfileComplete(): bool
    {
        return !empty(trim((string) $this->name))
            && !empty(trim((string) $this->email))
            && !empty(trim((string) $this->phone))
            && !empty(trim((string) $this->address));
    }

    /**
     * Inisial nama pengguna:
     * - 1 kata: 1 huruf (Karel => K)
     * - 2 kata: 2 huruf (Karel Kornelius => KK)
     * - 3 kata atau lebih: 3 huruf awal dari 3 kata pertama (Karel Kornelius Alexander => KKA)
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->name), -1, PREG_SPLIT_NO_EMPTY);
        if (empty($words)) {
            return 'U';
        }

        $initials = '';
        $maxWords = min(3, count($words));
        for ($i = 0; $i < $maxWords; $i++) {
            $initials .= mb_substr($words[$i], 0, 1);
        }

        return mb_strtoupper($initials);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
