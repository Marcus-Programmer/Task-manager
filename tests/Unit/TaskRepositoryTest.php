<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TaskRepository $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
    }

    /**
     * Test task repository can create a new task
     */
    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'user_id' => $user->id,
        ];

        $task = $this->taskRepository->create($taskData);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals($user->id, $task->user_id);
    }

    /**
     * Test task repository can update a task
     */
    public function test_can_update_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'status' => 'pending',
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'status' => 'in_progress',
        ];

        $updatedTask = $this->taskRepository->update($task, $updateData);

        $this->assertEquals('Updated Title', $updatedTask->title);
        $this->assertEquals('in_progress', $updatedTask->status);
    }

    /**
     * Test task repository can delete a task
     */
    public function test_can_delete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $result = $this->taskRepository->delete($task);

        $this->assertTrue($result);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /**
     * Test task repository can find task by id and user
     */
    public function test_can_find_task_by_id_and_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $otherUser = User::factory()->create();

        $foundTask = $this->taskRepository->findByIdAndUser($task->id, $user);
        $notFoundTask = $this->taskRepository->findByIdAndUser($task->id, $otherUser);

        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals($task->id, $foundTask->id);
        $this->assertNull($notFoundTask);
    }

    /**
     * Test task repository can get all tasks for user with filters
     */
    public function test_can_get_all_tasks_for_user_with_filters(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'in_progress']);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'done']);
        Task::factory()->create(['user_id' => $otherUser->id, 'status' => 'pending']);

        // Test without filters
        $allTasks = $this->taskRepository->getAllForUser($user);
        $this->assertEquals(3, $allTasks->total());

        // Test with status filter
        $pendingTasks = $this->taskRepository->getAllForUser($user, ['status' => 'pending']);
        $this->assertEquals(1, $pendingTasks->total());
        $this->assertEquals('pending', $pendingTasks->first()->status);
    }
}
