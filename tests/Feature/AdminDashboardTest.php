<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_non_admin_users_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_sign_in_and_view_live_dashboard_metrics(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);
        $customer = Customer::create([
            'name' => 'Avery Customer',
            'email' => 'avery@example.com',
        ]);
        Product::create([
            'name' => 'Linen Table Runner',
            'category' => 'Textiles',
            'price' => 42.50,
            'quantity' => 3,
            'status' => 'active',
        ]);
        Order::create([
            'order_number' => 'FF-1001',
            'customer_id' => $customer->id,
            'total' => 42.50,
            'status' => 'delivered',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Linen Table Runner')
            ->assertSee('Avery Customer')
            ->assertSee('FF-1001')
            ->assertSee('$42.50')
            ->assertSee('1 low in stock');
    }

    public function test_admin_can_sign_out(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
