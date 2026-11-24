<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
    }

    public function test_admin_can_create_view_update_and_delete_a_product(): void
    {
        $this->post(route('admin.products.store'), [
            'name' => 'Linen Table Runner',
            'description' => 'A hand-finished linen runner.',
            'category' => 'Textiles',
            'price' => '42.50',
            'quantity' => 3,
            'status' => 'active',
            'image' => UploadedFile::fake()->createWithContent(
                'runner.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
            ),
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::firstOrFail();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Linen Table Runner',
            'price' => '42.50',
            'quantity' => 3,
        ]);
        Storage::disk('public')->assertExists($product->image);

        $this->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Linen Table Runner')
            ->assertSee('Textiles');

        $this->put(route('admin.products.update', $product), [
            'name' => 'Linen Table Runner - Natural',
            'description' => 'Updated product details.',
            'category' => 'Home textiles',
            'price' => '45.00',
            'quantity' => 8,
            'status' => 'inactive',
        ])->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Linen Table Runner - Natural', $product->name);
        $this->assertSame('45.00', $product->price);
        $this->assertSame('inactive', $product->status);
        Storage::disk('public')->assertExists($product->image);

        $this->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing($product->image);
    }

    public function test_product_search_matches_name_and_category(): void
    {
        Product::create([
            'name' => 'Linen Table Runner',
            'category' => 'Textiles',
            'price' => 42.50,
            'quantity' => 3,
            'status' => 'active',
        ]);
        Product::create([
            'name' => 'Ceramic Vase',
            'category' => 'Home decor',
            'price' => 29.00,
            'quantity' => 12,
            'status' => 'active',
        ]);

        $this->get(route('admin.products.index', ['q' => 'Textiles']))
            ->assertOk()
            ->assertSee('Linen Table Runner')
            ->assertDontSee('Ceramic Vase');
    }

    public function test_guest_cannot_manage_products(): void
    {
        auth()->logout();

        $this->get(route('admin.products.index'))
            ->assertRedirect(route('login'));
    }
}
