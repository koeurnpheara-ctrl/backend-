<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_api_returns_only_active_products_with_public_image_urls(): void
    {
        Storage::fake('public');
        $imagePath = 'products/runner.png';
        Storage::disk('public')->put($imagePath, 'image contents');

        Product::create([
            'name' => 'Linen Table Runner',
            'description' => 'A hand-finished linen runner.',
            'category' => 'Textiles',
            'price' => '42.50',
            'image' => $imagePath,
            'quantity' => 3,
            'status' => 'active',
        ]);
        Product::create([
            'name' => 'Hidden Draft',
            'price' => '12.00',
            'quantity' => 1,
            'status' => 'inactive',
        ]);

        $this->withHeader('Origin', 'http://16.176.28.191')
            ->getJson('/api/products')
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'http://16.176.28.191')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Linen Table Runner')
            ->assertJsonPath('data.0.category', 'Textiles')
            ->assertJsonPath('data.0.price', 42.5)
            ->assertJsonPath('data.0.image_url', Storage::disk('public')->url($imagePath));
    }

    public function test_storefront_api_returns_distinct_categories_from_active_products(): void
    {
        Product::create([
            'name' => 'Runner',
            'category' => 'Textiles',
            'price' => 20,
            'quantity' => 2,
            'status' => 'active',
        ]);
        Product::create([
            'name' => 'Napkins',
            'category' => 'Textiles',
            'price' => 15,
            'quantity' => 4,
            'status' => 'active',
        ]);
        Product::create([
            'name' => 'Private sample',
            'category' => 'Samples',
            'price' => 5,
            'quantity' => 1,
            'status' => 'inactive',
        ]);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Textiles');
    }
}
