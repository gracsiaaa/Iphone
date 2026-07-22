<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'store_name',
        'address',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (blank($user->username)) {
                $user->username = static::generateUniqueUsername(
                    $user->email ?: $user->name
                );
            }
        });
    }

    public static function generateUniqueUsername(string $source): string
    {
        $source = Str::before($source, '@');
        $source = Str::ascii(Str::lower($source));
        $base = preg_replace('/[^a-z0-9._]+/', '.', $source) ?: 'user';
        $base = trim(preg_replace('/[._]{2,}/', '.', $base), '._');
        $base = Str::limit($base ?: 'user', 24, '');

        if (strlen($base) < 4) {
            $base = Str::limit($base.'.user', 24, '');
        }

        $username = $base;
        $suffix = 1;

        while (static::query()->where('username', $username)->exists()) {
            $suffix++;
            $username = Str::limit($base, 24 - strlen((string) $suffix), '')
                .$suffix;
        }

        return $username;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function verifiedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'verified_by');
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            UserRole::ADMIN,
            UserRole::SUPERADMIN,
        ], true);
    }

    public function isSuperadmin(): bool
    {
        return $this->role === UserRole::SUPERADMIN;
    }
}
