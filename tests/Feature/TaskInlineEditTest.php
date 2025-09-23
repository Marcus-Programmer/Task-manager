<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskInlineEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_task_title_via_inline_edit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);

        $response = $this->actingAs($user)
            ->put("/tasks/{$task->id}", [
                'title' => 'Updated Title'
            ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task updated successfully.');

        $task->refresh();
        $this->assertEquals('Updated Title', $task->title);
        $this->assertEquals('Original Description', $task->description); // Should remain unchanged
    }

    public function test_can_update_task_description_via_inline_edit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Long Title',
            'description' => 'Original Description'
        ]);

        $response = $this->actingAs($user)
            ->put("/tasks/{$task->id}", [
                'description' => 'Updated Description'
            ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task updated successfully.');

        $task->refresh();
        $this->assertEquals('Original Long Title', $task->title); // Should remain unchanged
        $this->assertEquals('Updated Description', $task->description);
    }

    public function test_cannot_update_task_that_belongs_to_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->put("/tasks/{$task->id}", [
                'title' => 'Hacked Title'
            ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_validates_minimum_title_length_on_inline_edit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->put("/tasks/{$task->id}", [
                'title' => 'A' // Only 1 character, minimum is 3
            ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_validates_maximum_description_length_on_inline_edit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $longDescription = str_repeat('A', 1001); // Exceeds 1000 character limit

        $response = $this->actingAs($user)
            ->put("/tasks/{$task->id}", [
                'description' => $longDescription
            ]);

        $response->assertSessionHasErrors(['description']);
    }
}