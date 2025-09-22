<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::get('search/tasks', [TaskController::class, 'search'])->name('tasks.search');
});

require __DIR__.'/auth.php';
