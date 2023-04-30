<?php

namespace Tests\Feature\CommentController;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentControllerIndexTest extends TestCase
{
    public function test_can_get_comments_for_tasks(): void
    {
        $task = Task::factory()->create();
        Sanctum::actingAs($task->creator);
        Comment::factory()
            ->for($task, 'commentable')
            ->for($task->creator)
            ->count(3)
            ->create();

        $route = route('tasks.comments.index', $task);
        $response = $this->getJson($route);

        $response->assertOk();
    }

    public function test_can_get_comments_for_projects(): void
    {
        $project = Project::factory()->create();
        Sanctum::actingAs($project->creator);
        Comment::factory()
            ->for($project, 'commentable')
            ->for($project->creator)
            ->count(3)
            ->create();

        $route = route('projects.comments.index', $project);
        $response = $this->getJson($route);

        $response->assertOk();
    }
}
