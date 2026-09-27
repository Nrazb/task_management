<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $tasks = Task::with('user')
                ->when($request->search, function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully.',
                'data' => $tasks,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve tasks.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks.',
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = Task::with('user')->find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $task,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve task.', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task.',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        try {
            $task = Task::create($validated);

            Log::info('Task created.', [
                'task_id' => $task->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully.',
                'data' => $task,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Failed to create task.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create task.',
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:pending,in_progress,completed'],
            'priority' => ['sometimes', 'in:low,medium,high'],
        ]);

        try {
            $task->update($validated);

            Log::info('Task updated.', [
                'task_id' => $task->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully.',
                'data' => $task->fresh(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to update task.', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task.',
            ], 500);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        try {
            $task->update([
                'status' => $validated['status'],
            ]);

            Log::info('Task status updated.', [
                'task_id' => $task->id,
                'status' => $task->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully.',
                'data' => $task->fresh(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to update task status.', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status.',
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        try {
            $task->delete();

            Log::info('Task deleted.', [
                'task_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to delete task.', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task.',
            ], 500);
        }
    }
}
