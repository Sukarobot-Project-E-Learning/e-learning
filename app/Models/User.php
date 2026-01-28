<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'provider',
        'is_active',
        'avatar',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the user's avatar URL.
     * Overrides the raw avatar attribute.
     *
     * @return string
     */
    public function getAvatarAttribute($value): string
    {
        $defaultAvatar = asset('assets/elearning/client/img/default-avatar.jpeg');

        if (!$value) {
            return $defaultAvatar;
        }

        // If it's a valid URL, return it directly
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check availability in various locations
        if (file_exists(public_path($value))) {
            return asset($value);
        }

        if (file_exists(public_path('storage/' . $value))) {
            return asset('storage/' . $value);
        }

        if (file_exists(storage_path('app/public/' . $value))) {
            return asset('storage/' . $value);
        }

        // If file physically missing, return default
        return $defaultAvatar;
    }
}
