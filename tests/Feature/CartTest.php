<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
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

    // ─── Cart Page ────────────────────────────────────────────────────────────

    public function test_guest_cannot_access_cart(): void
    {
        $this->get('/cart')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_cart(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/cart')->assertStatus(200)->assertViewIs('cart.index');
    }

    // ─── Add to Cart ─────────────────────────────────────────────────────────

    public function test_user_can_add_product_to_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->postJson('/cart', [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Đã thêm sản phẩm vào giỏ hàng.', 'cart_count' => 2]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);
    }

    public function test_adding_same_product_twice_merges_quantity(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct(['stock' => 10]);

        $this->actingAs($user)->postJson('/cart', ['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user)->postJson('/cart', ['product_id' => $product->id, 'quantity' => 3]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);
    }

    public function test_cannot_add_more_than_stock(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct(['stock' => 3]);

        $response = $this->actingAs($user)->postJson('/cart', [
            'product_id' => $product->id,
            'quantity'   => 5,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
    }

    // ─── Update Cart ─────────────────────────────────────────────────────────

    public function test_user_can_update_cart_item_quantity(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct(['stock' => 10]);

        $this->actingAs($user)->postJson('/cart', ['product_id' => $product->id, 'quantity' => 2]);

        $cartItemId = \App\Models\CartItem::where('product_id', $product->id)->first()->id;

        $response = $this->actingAs($user)->patchJson("/cart/{$cartItemId}", ['quantity' => 4]);

        $response->assertStatus(200)->assertJsonStructure(['subtotal', 'cart_total']);
        $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 4]);
    }

    // ─── Remove from Cart ─────────────────────────────────────────────────────

    public function test_user_can_remove_item_from_cart(): void
    {
        $user    = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->postJson('/cart', ['product_id' => $product->id, 'quantity' => 1]);

        $cartItemId = \App\Models\CartItem::where('product_id', $product->id)->first()->id;

        $response = $this->actingAs($user)->deleteJson("/cart/{$cartItemId}");

        $response->assertStatus(200)->assertJson(['cart_count' => 0]);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
    }

    public function test_user_cannot_remove_other_users_cart_item(): void
    {
        $user1   = User::factory()->create();
        $user2   = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user1)->postJson('/cart', ['product_id' => $product->id, 'quantity' => 1]);
        $cartItemId = \App\Models\CartItem::where('product_id', $product->id)->first()->id;

        // User2 thử xóa cart item của User1
        $response = $this->actingAs($user2)->deleteJson("/cart/{$cartItemId}");
        $response->assertStatus(404); // CartItem không thuộc cart của user2
    }
}
