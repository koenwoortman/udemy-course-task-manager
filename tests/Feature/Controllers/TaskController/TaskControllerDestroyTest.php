<?php

namespace Tests\Feature\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerDestroyTest extends TestCase
{
    public function test_can_destroy_created_task(): void
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->creator);

        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', $task->toArray());
    }

    public function test_cannot_destroy_as_project_member(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->members()->attach([$user->id]);
        $task = Task::factory()->for($project->creator, 'creator')->for($project)->create();
        Sanctum::actingAs($user);

        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertForbidden();
    }

    public function test_unauthenticated_response(): void
    {
        $task = Task::factory()->create();

        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertUnauthorized();
    }

    public function test_no_access_response(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        Sanctum::actingAs($user);

        $route = route('tasks.destroy', $task);
        $response = $this->deleteJson($route);

        $response->assertNotFound();
    }
}
