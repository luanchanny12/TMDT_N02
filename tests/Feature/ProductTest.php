<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $attrs = []): Product
    {
        $category = Category::factory()->create();
        return Product::factory()->create(array_merge([
            'category_id' => $category->id,
            'status'      => 'active',
            'stock'       => 10,
        ], $attrs));
    }

    public function test_products_index_page_loads(): void
    {
        $this->createProduct();
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    }

    public function test_products_search_filter_works(): void
    {
        $this->createProduct(['name' => 'Áo thun đen']);
        $this->createProduct(['name' => 'Quần jean xanh']);

        $response = $this->get('/products?search=Áo thun');
        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('products'));
    }

    public function test_products_category_filter_works(): void
    {
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();
        Product::factory()->create(['category_id' => $cat1->id, 'status' => 'active']);
        Product::factory()->create(['category_id' => $cat1->id, 'status' => 'active']);
        Product::factory()->create(['category_id' => $cat2->id, 'status' => 'active']);

        $response = $this->get("/products?category={$cat1->id}");
        $this->assertEquals(2, $response->viewData('products')->total());
    }

    public function test_inactive_products_not_shown(): void
    {
        $this->createProduct(['status' => 'active']);
        $this->createProduct(['status' => 'inactive']);

        $response = $this->get('/products');
        $this->assertEquals(1, $response->viewData('products')->total());
    }

    public function test_product_detail_page_loads(): void
    {
        $product  = $this->createProduct(['slug' => 'ao-thun-test']);
        $response = $this->get("/products/ao-thun-test");
        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('product', fn ($p) => $p->id === $product->id);
    }

    public function test_inactive_product_detail_returns_404(): void
    {
        $this->createProduct(['slug' => 'san-pham-an', 'status' => 'inactive']);
        $this->get('/products/san-pham-an')->assertStatus(404);
    }

    public function test_home_page_loads_with_featured_products(): void
    {
        $this->createProduct();
        $this->get('/')->assertStatus(200)->assertViewIs('pages.home');
    }
}
