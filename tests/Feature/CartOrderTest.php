<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $userRole = Role::create(['name' => 'user']);
        $this->user = User::factory()->create();
        $this->user->roles()->attach($userRole);

        $category = Category::create(['title' => 'Giyim']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'user_id' => $this->user->id,
            'title' => 'Gömlek',
            'price' => 250.00,
            'stock' => 10,
        ]);
    }

    public function test_guest_cannot_access_cart()
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
        $this->post(route('cart.store'), ['product_id' => $this->product->id])->assertRedirect(route('login'));
    }

    public function test_user_can_add_product_to_cart()
    {
        $response = $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 250.00,
        ]);
    }

    public function test_adding_same_product_increments_quantity()
    {
        $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($this->user)->post(route('cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);
    }

    public function test_user_can_view_cart_items()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Gömlek');
    }

    public function test_user_can_remove_product_from_cart()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->delete(route('cart.destroy', $cart->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_user_can_checkout_cart_items()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->post(route('checkout'), [
            'name' => 'John Doe',
            'phone' => '5551234567',
            'address1' => 'Atatürk Mah.',
            'city' => 'Istanbul',
            'country' => 'Turkey',
            'zipcode' => '34000',
            'payment_method' => 'Credit Card',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'name' => 'John Doe',
            'total_amount' => 500.00,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'price' => 250.00,
            'quantity' => 2,
        ]);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_access_checkout_page()
    {
        $response = $this->actingAs($this->user)->get(route('checkout.form'));

        $response->assertStatus(200);
        $response->assertSee('Teslimat ve Ödeme Bilgileri');
    }
}
