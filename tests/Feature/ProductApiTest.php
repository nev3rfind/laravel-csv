<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductCacheTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_caches_product_retrieval_by_sku()
    {
        // Create a new user and a product with a known SKU
        $user = User::factory()->create([
            // Not necessary to encrypt but jwt might not like it othwerwise
            'password' => bcrypt($password = 'mkm-user-test'),
        ]);

        $product = Product::factory()->create(['sku' => 'SKU001']);

        // Login to get token
        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $token = $response->json()['authorization']['token'];

        // Perform the initial request to populate the cache with an authenticated user
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', "/api/product/{$product->sku}");

        // Check if the product was returned properly
        $response->assertStatus(200)->assertJson($product->toArray());

        // Assert that the product is cached
        $cacheKey = 'product_' . $product->sku;
        $this->assertTrue(Cache::has($cacheKey));

        // Retrieve the product from cache and assert it matches
        $cachedProduct = Cache::get($cacheKey);
        $this->assertNotNull($cachedProduct);

        // Perform another request which should retrieve data from the cache
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', "/api/product/{$product->sku}");

        // Check that the product data retireved from the cache matches the original product data (problems with formating solved)
        $this->assertEquals($product->toArray(), (array) $cachedProduct);
    }
}
