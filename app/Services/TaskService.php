<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getAllTasksForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->taskRepository->getAllForUser($user, $filters);
    }

    public function createTask(User $user, array $data): Task
    {
        $data['user_id'] = $user->id;

        $this->validateTaskData($data);

        return $this->taskRepository->create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $this->validateUpdateData($data, $task->id);

        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(Task $task): bool
    {
        return $this->taskRepository->delete($task);
    }

    public function findTaskForUser(int $id, User $user): ?Task
    {
        return $this->taskRepository->findByIdAndUser($id, $user);
    }

    public function getTasksByStatus(User $user, string $status): LengthAwarePaginator
    {
        $this->validateStatus($status);

        return $this->taskRepository->getTasksByStatus($user, $status);
    }

    public function searchTasks(User $user, string $search): LengthAwarePaginator
    {
        if (strlen(trim($search)) < 2) {
            throw ValidationException::withMessages([
                'search' => 'Search term must be at least 2 characters long.'
            ]);
        }

        return $this->taskRepository->searchTasks($user, $search);
    }

    public function changeTaskStatus(Task $task, string $newStatus): Task
    {
        $this->validateStatus($newStatus);

        return $this->taskRepository->update($task, ['status' => $newStatus]);
    }

    private function validateTaskData(array $data, ?int $excludeId = null): void
    {
        if (empty($data['title']) || strlen(trim($data['title'])) < 3) {
            throw ValidationException::withMessages([
                'title' => 'Task title must be at least 3 characters long.'
            ]);
        }

        if (isset($data['status'])) {
            $this->validateStatus($data['status']);
        }
    }

    private function validateUpdateData(array $data, ?int $excludeId = null): void
    {
        // Only validate title if it's being updated
        if (isset($data['title'])) {
            if (empty($data['title']) || strlen(trim($data['title'])) < 3) {
                throw ValidationException::withMessages([
                    'title' => 'Task title must be at least 3 characters long.'
                ]);
            }
        }

        // Only validate status if it's being updated
        if (isset($data['status'])) {
            $this->validateStatus($data['status']);
        }
    }

    private function validateStatus(string $status): void
    {
        $validStatuses = ['pending', 'in_progress', 'done'];

        if (!in_array($status, $validStatuses)) {
            throw ValidationException::withMessages([
                'status' => 'Invalid status. Must be one of: ' . implode(', ', $validStatuses)
            ]);
        }
    }
}