<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.users.store'), [
                'name' => 'Jane',
                'email' => 'jane@example.com',
                'password' => 'secret-password',
                'is_admin' => '1',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'is_admin' => true,
        ]);
        $this->assertTrue(Hash::check('secret-password', User::firstWhere('email', 'jane@example.com')->password));
    }

    public function test_store_requires_password_on_create(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'No Pass',
                'email' => 'nopass@example.com',
            ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_store_rejects_short_password(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Short',
                'email' => 'short@example.com',
                'password' => 'abc',
            ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors(['password']);
    }

    public function test_store_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dupe@example.com']);

        $response = $this->actingAs($this->admin())
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'Dupe',
                'email' => 'dupe@example.com',
                'password' => 'secret-password',
            ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_keeps_password_when_left_blank(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        $response = $this->actingAs($this->admin())
            ->put(route('admin.users.update', $user), [
                'name' => 'Updated Name',
                'email' => $user->email,
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSame('Updated Name', $user->fresh()->name);
        $this->assertTrue(Hash::check('original-password', $user->fresh()->password));
    }

    public function test_update_allows_same_email_for_same_user(): void
    {
        $user = User::factory()->create(['email' => 'keep@example.com']);

        $response = $this->actingAs($this->admin())
            ->put(route('admin.users.update', $user), [
                'name' => 'Keep Email',
                'email' => 'keep@example.com',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHasNoErrors();
    }
}
