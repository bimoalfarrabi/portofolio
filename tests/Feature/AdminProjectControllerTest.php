<?php

namespace Tests\Feature;

use App\Models\PortfolioProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_cannot_access_project_index(): void
    {
        $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_project_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.projects.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_project_with_uploaded_image(): void
    {
        Storage::fake('public');

        $payload = [
            'title' => 'New Project',
            'type' => 'open',
            'category' => 'Web App',
            'year' => '2026',
            'description' => 'Sample description.',
            'approach' => 'Sample approach.',
            'stack' => 'Laravel, React, Tailwind',
            'outcome' => 'Sample outcome.',
            'sort_order' => 0,
            'is_published' => '1',
            'image_file' => UploadedFile::fake()->image('cover.png', 800, 450),
        ];

        $response = $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), $payload);

        $response->assertRedirect(route('admin.projects.index'));
        $response->assertSessionHas('status', 'Project tersimpan.');

        $project = PortfolioProject::firstWhere('title', 'New Project');
        $this->assertNotNull($project);
        $this->assertSame(['Laravel', 'React', 'Tailwind'], $project->stack);
        $this->assertTrue($project->is_published);
        $this->assertStringStartsWith('projects/', $project->image);
        Storage::disk('public')->assertExists($project->image);
    }

    public function test_store_rejects_invalid_type(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.projects.create'))
            ->post(route('admin.projects.store'), [
                'title' => 'Bad type',
                'type' => 'private',
            ]);

        $response->assertRedirect(route('admin.projects.create'));
        $response->assertSessionHasErrors(['type']);
        $this->assertDatabaseMissing('portfolio_projects', ['title' => 'Bad type']);
    }

    public function test_store_requires_title(): void
    {
        $response = $this->actingAs($this->admin())
            ->from(route('admin.projects.create'))
            ->post(route('admin.projects.store'), [
                'type' => 'open',
            ]);

        $response->assertRedirect(route('admin.projects.create'));
        $response->assertSessionHasErrors(['title']);
    }

    public function test_update_keeps_existing_image_when_no_new_input(): void
    {
        Storage::fake('public');

        $project = PortfolioProject::create([
            'title' => 'Existing',
            'type' => 'open',
            'image' => 'projects/old.png',
            'stack' => [],
            'is_published' => true,
        ]);
        Storage::disk('public')->put('projects/old.png', 'content');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), [
                'title' => 'Existing',
                'type' => 'open',
                'stack' => '',
            ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertSame('projects/old.png', $project->fresh()->image);
        Storage::disk('public')->assertExists('projects/old.png');
    }

    public function test_update_can_remove_image(): void
    {
        Storage::fake('public');

        $project = PortfolioProject::create([
            'title' => 'To remove',
            'type' => 'open',
            'image' => 'projects/keep.png',
            'stack' => [],
            'is_published' => true,
        ]);
        Storage::disk('public')->put('projects/keep.png', 'content');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), [
                'title' => 'To remove',
                'type' => 'open',
                'remove_image' => '1',
                'stack' => '',
            ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertNull($project->fresh()->image);
        Storage::disk('public')->assertMissing('projects/keep.png');
    }

    public function test_destroy_deletes_local_image_file(): void
    {
        Storage::fake('public');

        $project = PortfolioProject::create([
            'title' => 'Delete me',
            'type' => 'open',
            'image' => 'projects/delete.png',
            'stack' => [],
            'is_published' => true,
        ]);
        Storage::disk('public')->put('projects/delete.png', 'content');

        $response = $this->actingAs($this->admin())
            ->delete(route('admin.projects.destroy', $project));

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseMissing('portfolio_projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing('projects/delete.png');
    }

    public function test_admin_can_create_project_with_multiple_gallery_files(): void
    {
        Storage::fake('public');

        $payload = [
            'title' => 'Gallery Project',
            'type' => 'open',
            'stack' => '',
            'is_published' => '1',
            'gallery_files' => [
                UploadedFile::fake()->image('one.png', 800, 450),
                UploadedFile::fake()->image('two.png', 800, 450),
                UploadedFile::fake()->image('three.png', 800, 450),
            ],
        ];

        $response = $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), $payload);

        $response->assertRedirect(route('admin.projects.index'));
        $project = PortfolioProject::firstWhere('title', 'Gallery Project');
        $this->assertNotNull($project);
        $this->assertCount(3, $project->gallery);
        foreach ($project->gallery as $stored) {
            $this->assertStringStartsWith('projects/', $stored);
            Storage::disk('public')->assertExists($stored);
        }
        $this->assertCount(3, $project->gallery_urls);
    }

    public function test_update_can_remove_gallery_items_and_add_more(): void
    {
        Storage::fake('public');

        $project = PortfolioProject::create([
            'title' => 'Edit Gallery',
            'type' => 'open',
            'gallery' => ['projects/keep.png', 'projects/drop.png'],
            'stack' => [],
            'is_published' => true,
        ]);
        Storage::disk('public')->put('projects/keep.png', 'k');
        Storage::disk('public')->put('projects/drop.png', 'd');

        $response = $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), [
                'title' => 'Edit Gallery',
                'type' => 'open',
                'stack' => '',
                'remove_gallery' => ['projects/drop.png'],
                'gallery_files' => [
                    UploadedFile::fake()->image('new.png', 800, 450),
                ],
            ]);

        $response->assertRedirect(route('admin.projects.index'));
        $fresh = $project->fresh();
        $this->assertContains('projects/keep.png', $fresh->gallery);
        $this->assertNotContains('projects/drop.png', $fresh->gallery);
        $this->assertCount(2, $fresh->gallery);
        Storage::disk('public')->assertMissing('projects/drop.png');
        Storage::disk('public')->assertExists('projects/keep.png');
    }

    public function test_destroy_deletes_gallery_files(): void
    {
        Storage::fake('public');

        $project = PortfolioProject::create([
            'title' => 'Bye Gallery',
            'type' => 'open',
            'gallery' => ['projects/g1.png', 'projects/g2.png'],
            'stack' => [],
            'is_published' => true,
        ]);
        Storage::disk('public')->put('projects/g1.png', '1');
        Storage::disk('public')->put('projects/g2.png', '2');

        $this->actingAs($this->admin())
            ->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        Storage::disk('public')->assertMissing('projects/g1.png');
        Storage::disk('public')->assertMissing('projects/g2.png');
    }
}
