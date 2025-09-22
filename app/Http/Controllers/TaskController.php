<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $tasks = $this->taskService->getAllTasksForUser(auth()->user(), $filters);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask(auth()->user(), $request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => $task->load('user'),
        ]);
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task = $this->taskService->updateTask($task, $request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');

        if (strlen(trim($search)) < 2) {
            return back()->withErrors(['search' => 'Search term must be at least 2 characters long.']);
        }

        $tasks = $this->taskService->searchTasks(auth()->user(), $search);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => ['search' => $search],
        ]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,done'
        ]);

        $task = $this->taskService->changeTaskStatus($task, $request->status);

        return back()->with('success', 'Task status updated successfully.');
    }
}
