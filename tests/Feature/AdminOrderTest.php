<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->user = User::factory()->create();
        $this->user->roles()->attach($userRole);

        $category = Category::create(['title' => 'Gıda']);
        $product = Product::create([
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
            'title' => 'Kahve',
            'price' => 120.00,
            'stock' => 10,
        ]);

        $this->order = Order::create([
            'user_id' => $this->user->id,
            'name' => 'Jane Doe',
            'phone' => '5559876543',
            'address1' => 'Merkez Mah.',
            'city' => 'Ankara',
            'country' => 'Turkey',
            'zipcode' => '06000',
            'total_amount' => 240.00,
            'payment_method' => 'Cash',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
            'price' => 120.00,
            'quantity' => 2,
        ]);
    }

    public function test_guest_cannot_access_admin_orders()
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
        $this->get(route('admin.orders.show', $this->order->id))->assertRedirect(route('login'));
        $this->put(route('admin.orders.update', $this->order->id), ['status' => 'accepted'])->assertRedirect(route('login'));
    }

    public function test_user_cannot_access_admin_orders()
    {
        $this->actingAs($this->user)->get(route('admin.orders.index'))->assertStatus(403);
        $this->actingAs($this->user)->get(route('admin.orders.show', $this->order->id))->assertStatus(403);
        $this->actingAs($this->user)->put(route('admin.orders.update', $this->order->id), ['status' => 'accepted'])->assertStatus(403);
    }

    public function test_admin_can_list_orders()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Jane Doe', 'total_amount' => 240]);
    }

    public function test_admin_can_view_order_details()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Jane Doe', 'title' => 'Kahve']);
    }

    public function test_admin_can_update_order_status()
    {
        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), [
            'status' => 'accepted',
            'tracking_no' => 'TRK-1234567890',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'accepted',
            'tracking_no' => 'TRK-1234567890',
        ]);
    }

    public function test_admin_cannot_update_order_with_invalid_status()
    {
        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order->id), [
            'status' => 'invalid-status',
        ]);

        $response->assertSessionHasErrors('status');
    }
}
