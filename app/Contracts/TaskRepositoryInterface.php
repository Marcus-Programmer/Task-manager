<?php

namespace App\Contracts;

use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function getAllForUser(User $user, array $filters = []): LengthAwarePaginator;

    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;

    public function findByIdAndUser(int $id, User $user): ?Task;

    public function getTasksByStatus(User $user, string $status): LengthAwarePaginator;

    public function searchTasks(User $user, string $search): LengthAwarePaginator;
}