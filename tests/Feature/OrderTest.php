<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
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

    public function test_guest_cannot_checkout(): void
    {
        $this->post('/checkout')->assertRedirect('/login');
    }

    public function test_cannot_checkout_with_empty_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '0987654321',
            'shipping_address' => '123 Test St',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Giỏ hàng của bạn đang trống.');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_created_successfully_and_stock_decremented(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5, 'price' => 200000]);

        // Add to cart manually via DB for testing
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 200000,
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '0987654321',
            'shipping_address' => '123 Test St',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check order created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'subtotal' => 400000,
            'shipping_fee' => 30000, // < 500k -> 30k fee
            'total' => 430000,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        // Check stock decremented
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3, // 5 - 2
        ]);

        // Check cart cleared
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
        ]);
    }

    public function test_free_shipping_applied_when_subtotal_exceeds_threshold(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 5, 'price' => 600000]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 600000,
        ]);

        $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John',
            'shipping_phone' => '098',
            'shipping_address' => '123',
            'payment_method' => 'cod',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'subtotal' => 600000,
            'shipping_fee' => 0, // >= 500k -> 0 fee
            'total' => 600000,
        ]);
    }

    public function test_cannot_checkout_if_stock_insufficient(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(['stock' => 2]); // only 2 in stock

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3, // ordering 3
            'price' => 100000,
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John',
            'shipping_phone' => '098',
            'shipping_address' => '123',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);

        // Stock shouldn't change
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 2,
        ]);
    }
}
