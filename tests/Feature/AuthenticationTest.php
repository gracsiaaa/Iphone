<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'username' => 'demo.reseller',
            'email' => 'reseller@example.com',
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'demo.reseller',
            'email' => 'reseller@example.com',
            'password' => 'Password123!',
        ]);

        $response = $this->post('/login', [
            'login' => 'demo.reseller',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_register_without_phone_number(): void
    {
        $response = $this->post('/register', [
            'name' => 'Reseller Baru',
            'username' => 'reseller.baru',
            'email' => 'baru@example.com',
            'store_name' => 'Toko Baru',
            'address' => 'Jakarta',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'username' => 'reseller.baru',
            'email' => 'baru@example.com',
            'phone' => null,
        ]);
    }
}
