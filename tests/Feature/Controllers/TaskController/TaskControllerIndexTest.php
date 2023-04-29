<?php

namespace Tests\Feature\TaskController;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerIndexTest extends TestCase
{
    public function test_authenticated_users_can_fetch_the_task_list(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Task::factory()->for($user, 'creator')->create();

        $route = route('tasks.index');
        $response = $this->getJson($route);

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'is_done',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /**
     * @dataProvider sortableFields
     */
    public function test_sortable_fields($field, $expectedCode): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('tasks.index', [
            'sort' => $field,
        ]);

        $response = $this->getJson($route);
        $response->assertStatus($expectedCode);
    }

    public function sortableFields(): array
    {
        return [
            ['id', 400],
            ['title',  200],
            ['is_done', 200],
            ['created_at', 200],
            ['updated_at', 400],
        ];
    }

    /**
     * @dataProvider filterFields
     */
    public function test_filterable_fields($field, $value, $expectedCode): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $route = route('tasks.index', [
            "filter[{$field}]" => $value,
        ]);

        $response = $this->getJson($route);
        $response->assertStatus($expectedCode);
    }

    public function filterFields(): array
    {
        return [
            ['id', 1, 400],
            ['title', 'foo', 400],
            ['is_done', 1, 200],
        ];
    }

    public function test_unauthenticated_users_can_not_fetch_tasks(): void
    {
        $route = route('tasks.index');
        $response = $this->getJson($route);

        $response->assertUnauthorized();
    }
}
