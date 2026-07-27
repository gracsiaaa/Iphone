<?php

namespace Database\Factories;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $username = Str::lower(fake()->unique()->userName());
        $username = preg_replace('/[^a-z0-9._]+/', '.', $username) ?: 'user';
        $username = trim($username, '._');

        if (strlen($username) < 4) {
            $username .= '.user';
        }

        return [
            'name' => fake()->name(),
            'username' => Str::limit($username, 30, ''),
            'email' => fake()->unique()->safeEmail(),
            'phone' => null,
            'store_name' => fake()->company(),
            'address' => fake()->address(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::USER,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }
}
