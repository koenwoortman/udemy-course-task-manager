<?php

namespace Tests\Feature\TaskController;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerIndexTest extends TestCase
{
    public function test_authenticated_users_can_fetch_the_task_list(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $route = route('tasks.index');
        $response = $this->getJson($route);

        $response->assertOk();
    }

    public function test_unauthenticated_users_can_not_fetch_tasks(): void
    {
        $route = route('tasks.index');
        $response = $this->getJson($route);

        $response->assertUnauthorized();
    }
}
