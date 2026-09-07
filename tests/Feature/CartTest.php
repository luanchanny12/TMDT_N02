<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $attrs = []): Product
    {
        $cat = Category::factory()->create();
        return Product::factory()->create(array_merge([
            'category_id' => $cat->id,
            'status'      => 'active',
            'price'       => 100000,
            'sale_price'  => null,
            'stock'       => 10,
        ], $attrs));
    }

    public function test_guest_cannot_access_cart(): void
    {
        $this->get('/cart')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_cart(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/cart')->assertStatus(200)->assertViewIs('cart.index');
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $response->assertRedirect();

        $cartItems = Cart::getContent();
        $this->assertEquals(1, $cartItems->count());
        $this->assertEquals($product->name, $cartItems->first()->name);
    }

    public function test_adding_same_product_increases_quantity(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct(['stock' => 10]);

        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 3]);

        $cartItems = Cart::getContent();
        $this->assertEquals(1, $cartItems->count());
        $this->assertEquals(5, $cartItems->first()->quantity);
    }

    public function test_user_can_remove_item_from_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $cartItems = Cart::getContent();
        $firstItem = $cartItems->first();

        $this->actingAs($user)->post('/cart/remove', ['rowId' => $firstItem->id]);

        $this->assertEquals(0, Cart::getTotalQuantity());
    }

    public function test_user_can_clear_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($user)->post('/cart/clear');

        $this->assertEquals(0, Cart::getTotalQuantity());
    }
}
