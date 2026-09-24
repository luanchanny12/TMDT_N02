<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_xml(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');
    }

    public function test_sitemap_contains_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee($product->slug);
    }

    public function test_sitemap_contains_static_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('huong-dan-mua-hang')
            ->assertSee('chinh-sach-doi-tra')
            ->assertSee('chinh-sach-bao-mat')
            ->assertSee('dieu-khoan-giao-dich')
            ->assertSee('gioi-thieu')
            ->assertSee('faq');
    }

    public function test_sitemap_contains_category_slug_urls(): void
    {
        Category::factory()->create([
            'slug' => 'danh-muc-sitemap-test',
            'parent_id' => null,
            'status' => 'active',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/danh-muc/danh-muc-sitemap-test')
            ->assertDontSee('/products?category=');
    }
}
