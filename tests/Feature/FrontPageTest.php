<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontPageTest extends TestCase
{
    use RefreshDatabase;

    protected Category $activeCategory;
    protected Category $inactiveCategory;
    protected Product $activeProduct;
    protected Product $inactiveProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $this->activeCategory = Category::create([
            'title' => 'Aktif Kategori',
            'status' => true,
        ]);

        $this->inactiveCategory = Category::create([
            'title' => 'Pasif Kategori',
            'status' => false,
        ]);

        $this->activeProduct = Product::create([
            'category_id' => $this->activeCategory->id,
            'user_id' => $admin->id,
            'title' => 'Aktif Urun',
            'price' => 100.00,
            'stock' => 50,
            'status' => true,
        ]);

        $this->inactiveProduct = Product::create([
            'category_id' => $this->activeCategory->id,
            'user_id' => $admin->id,
            'title' => 'Pasif Urun',
            'price' => 100.00,
            'stock' => 50,
            'status' => false,
        ]);
    }

    public function test_home_page_shows_active_categories_and_products()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Aktif Kategori');
        $response->assertSee('Aktif Urun');
        
        $response->assertDontSee('Pasif Kategori');
        $response->assertDontSee('Pasif Urun');
    }

    public function test_category_products_page_shows_correct_products()
    {
        $response = $this->get(route('category.products', $this->activeCategory->id));

        $response->assertStatus(200);
        $response->assertSee('Aktif Urun');
        $response->assertDontSee('Pasif Urun');
    }

    public function test_product_detail_page_loads_correctly()
    {
        $response = $this->get(route('product.detail', $this->activeProduct->id));

        $response->assertStatus(200);
        $response->assertSee('Aktif Urun');
        $response->assertSee('50 adet');
    }

    public function test_inactive_product_detail_page_returns_404()
    {
        $response = $this->get(route('product.detail', $this->inactiveProduct->id));

        $response->assertStatus(404);
    }
}
