<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User yang sedang login tidak dapat menghapus dirinya sendiri.');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $target = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $target));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User dihapus.');
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_admin_cannot_toggle_self_role(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-admin', $admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User yang sedang login tidak dapat mengubah role dirinya sendiri.');
        $this->assertTrue($admin->fresh()->is_admin);
    }

    public function test_admin_can_toggle_other_user_role(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $target = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-admin', $target));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User dijadikan admin.');
        $this->assertTrue($target->fresh()->is_admin);
    }
}
