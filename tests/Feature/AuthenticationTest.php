<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_user_can_login_and_access_protected_route()
    {
        $product = Product::factory()->create();

        // Register a new user
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $this->json('POST', '/api/register', $userData)
             ->assertStatus(200);

        // Attempt to login with the registered user
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $token = $response->json()['authorization']['token'];

        // Access the protected route using the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', "/api/product/{$product->sku}");

        $response->assertStatus(200);
    }
}
