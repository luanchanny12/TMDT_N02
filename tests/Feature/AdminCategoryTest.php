<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-category-test@socialshop.vn',
        ]);

        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_categories_index(): void
    {
        Category::factory()->count(2)->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_root_category(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Danh mục cấp 1',
            'parent_id' => '',
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Danh mục cấp 1',
            'parent_id' => null,
        ]);
    }

    public function test_admin_can_create_child_category(): void
    {
        $parent = Category::factory()->create(['parent_id' => null]);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Danh mục con',
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Danh mục con',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_admin_cannot_create_third_level_category(): void
    {
        $root = Category::factory()->create(['parent_id' => null]);
        $child = Category::factory()->child($root->id)->create();

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Danh mục cấp 3',
            'parent_id' => $child->id,
        ]);

        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseMissing('categories', ['name' => 'Danh mục cấp 3']);
    }

    public function test_admin_cannot_set_self_as_parent(): void
    {
        $category = Category::factory()->create(['parent_id' => null]);

        $response = $this->put(
            route('admin.categories.update', ['category' => $category->id]),
            [
                'name' => $category->name,
                'parent_id' => $category->id,
            ]
        );

        $response->assertSessionHasErrors('parent_id');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'parent_id' => null,
        ]);
    }

    public function test_admin_can_update_category_parent(): void
    {
        $parent = Category::factory()->create(['parent_id' => null]);
        $category = Category::factory()->create(['parent_id' => null]);

        $response = $this->put(
            route('admin.categories.update', ['category' => $category->id]),
            [
                'name' => $category->name,
                'parent_id' => $parent->id,
            ]
        );

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'parent_id' => $parent->id,
        ]);
    }

    public function test_admin_can_move_category_to_root(): void
    {
        $parent = Category::factory()->create(['parent_id' => null]);
        $category = Category::factory()->child($parent->id)->create();

        $response = $this->put(
            route('admin.categories.update', ['category' => $category->id]),
            [
                'name' => $category->name,
                'parent_id' => '',
            ]
        );

        $response->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'parent_id' => null,
        ]);
    }

    public function test_admin_cannot_set_nonexistent_parent(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Danh mục lỗi',
            'parent_id' => 999999,
        ]);

        $response->assertSessionHasErrors('parent_id');
    }

    public function test_customer_cannot_access_admin_categories(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.categories.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_categories(): void
    {
        $this->app['auth']->forgetGuards();
        $this->flushSession();

        $response = $this->get(route('admin.categories.index'));

        $response->assertRedirect('/login');
    }
}
