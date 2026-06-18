<?php

namespace Tests\Feature;

use App\Models\PortfolioLog;
use App\Models\PortfolioSkill;
use App\Models\PortfolioStat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_create_skill(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.skills.store'), [
                'name' => 'React',
                'category' => 'Frontend',
                'sort_order' => 2,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseHas('portfolio_skills', [
            'name' => 'React',
            'category' => 'Frontend',
            'is_active' => true,
        ]);
    }

    public function test_skill_requires_name(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.skills.create'))
            ->post(route('admin.skills.store'), []);

        $response->assertRedirect(route('admin.skills.create'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_non_admin_cannot_create_skill(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->post(route('admin.skills.store'), ['name' => 'React'])
            ->assertForbidden();

        $this->assertDatabasemissing('portfolio_skills', ['name' => 'React']);
    }

    public function test_log_tags_are_parsed_into_array(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.logs.store'), [
                'title' => 'Release notes',
                'tags' => 'laravel, react ,, tailwind',
                'is_published' => '1',
            ]);

        $response->assertRedirect(route('admin.logs.index'));

        $log = PortfolioLog::firstWhere('title', 'Release notes');
        $this->assertNotNull($log);
        $this->assertSame(['laravel', 'react', 'tailwind'], $log->tags);
        $this->assertTrue($log->is_published);
    }

    public function test_admin_can_create_stat(): void
    {
        $response = $this->actingAs($this->admin())
            ->post(route('admin.stats.store'), [
                'key' => 'years',
                'label' => 'Years',
                'value' => '5',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.stats.index'));
        $this->assertDatabaseHas('portfolio_stats', [
            'key' => 'years',
            'label' => 'Years',
            'value' => '5',
        ]);
    }

    public function test_stat_requires_key_label_value(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.stats.create'))
            ->post(route('admin.stats.store'), []);

        $response->assertRedirect(route('admin.stats.create'));
        $response->assertSessionHasErrors(['key', 'label', 'value']);
    }

    public function test_admin_can_update_skill(): void
    {
        $skill = PortfolioSkill::create([
            'name' => 'Vue',
            'category' => 'Frontend',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin())
            ->put(route('admin.skills.update', $skill), [
                'name' => 'Vue 3',
                'category' => 'Frontend',
            ]);

        $response->assertRedirect(route('admin.skills.index'));
        $this->assertSame('Vue 3', $skill->fresh()->name);
        $this->assertFalse($skill->fresh()->is_active);
    }

    public function test_admin_can_delete_stat(): void
    {
        $stat = PortfolioStat::create([
            'key' => 'projects',
            'label' => 'Projects',
            'value' => '20',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.stats.destroy', $stat));

        $response->assertRedirect(route('admin.stats.index'));
        $this->assertDatabaseMissing('portfolio_stats', ['id' => $stat->id]);
    }
}
