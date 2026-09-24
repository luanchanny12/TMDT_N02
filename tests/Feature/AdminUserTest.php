<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_users_index(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $user = User::factory()->create(['name' => 'Người Dùng']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $user));

        $response->assertStatus(200)->assertSee('Người Dùng');
    }

    public function test_admin_can_update_user_role_and_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Tên cũ',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => 'Tên mới',
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Tên mới',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_lock_user_via_update(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'role' => 'customer',
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_update_requires_valid_role(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'role' => 'superuser',
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_admin_cannot_demote_self(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'name' => $this->admin->name,
            'role' => 'customer',
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_user_without_orders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_cannot_delete_user_with_orders(): void
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_other_admin(): void
    {
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $otherAdmin));

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.toggle-status', $user));

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_cannot_toggle_admin_status(): void
    {
        $otherAdmin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('admin.users.toggle-status', $otherAdmin));

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'is_active' => true,
        ]);
    }

    public function test_index_filters_by_role(): void
    {
        User::factory()->create(['role' => 'customer', 'name' => 'Khach Hang Mot']);
        User::factory()->create(['role' => 'admin', 'name' => 'Quan Tri Mot']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'admin']));

        $response->assertStatus(200)
            ->assertSee('Quan Tri Mot')
            ->assertDontSee('Khach Hang Mot');
    }

    public function test_index_filters_by_status(): void
    {
        User::factory()->create(['is_active' => true, 'name' => 'Active User']);
        User::factory()->create(['is_active' => false, 'name' => 'Locked User']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['status' => 'inactive']));

        $response->assertStatus(200)
            ->assertSee('Locked User')
            ->assertDontSee('Active User');
    }

    public function test_customer_cannot_access_admin_users(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_users(): void
    {
        $this->app['auth']->forgetGuards();
        $this->flushSession();

        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect('/login');
    }
}
