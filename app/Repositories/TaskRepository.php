<?php

namespace App\Repositories;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Task::forUser($user)->with('user');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function findByIdAndUser(int $id, User $user): ?Task
    {
        return Task::forUser($user)->find($id);
    }

    public function getTasksByStatus(User $user, string $status): LengthAwarePaginator
    {
        return Task::forUser($user)
            ->where('status', $status)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function searchTasks(User $user, string $search): LengthAwarePaginator
    {
        return Task::forUser($user)
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
}