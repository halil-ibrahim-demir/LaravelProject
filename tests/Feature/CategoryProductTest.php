<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->user = User::factory()->create();
        $this->user->roles()->attach($userRole);
    }

    public function test_admin_can_list_categories()
    {
        Category::create(['title' => 'Elektronik']);

        $response = $this->actingAs($this->admin)->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Elektronik']);
    }

    public function test_non_admin_cannot_list_categories()
    {
        $response = $this->actingAs($this->user)->get(route('categories.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'title' => 'Telefonlar',
            'status' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['title' => 'Telefonlar']);
    }

    public function test_category_relations()
    {
        $parent = Category::create(['title' => 'Bilgisayar']);
        $child = Category::create([
            'title' => 'Dizüstü',
            'parent_id' => $parent->id,
        ]);

        $this->assertEquals($parent->id, $child->parent->id);
        $this->assertTrue($parent->children->contains($child));
    }

    public function test_admin_can_create_product()
    {
        $category = Category::create(['title' => 'Moda']);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'category_id' => $category->id,
            'title' => 'T-shirt',
            'price' => 199.99,
            'stock' => 50,
            'minstock' => 5,
            'discount' => 10.00,
            'status' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'title' => 'T-shirt',
            'price' => 199.99,
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_product_belongs_to_category_and_user()
    {
        $category = Category::create(['title' => 'Moda']);
        $product = Product::create([
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
            'title' => 'T-shirt',
            'price' => 199.99,
            'stock' => 50,
        ]);

        $this->assertEquals($category->id, $product->category->id);
        $this->assertEquals($this->admin->id, $product->user->id);
    }

    public function test_admin_can_update_product()
    {
        $category = Category::create(['title' => 'Ev']);
        $product = Product::create([
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
            'title' => 'Lamba',
            'price' => 49.99,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->admin)->put(route('products.update', $product->id), [
            'category_id' => $category->id,
            'title' => 'Güncellenmiş Lamba',
            'price' => 59.99,
            'stock' => 12,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Güncellenmiş Lamba',
            'price' => 59.99,
        ]);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::create(['title' => 'Ev']);

        $response = $this->actingAs($this->admin)->delete(route('categories.destroy', $category->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
