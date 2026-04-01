<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignedTo', 'comments'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|min:2|max:255',
            'description' => 'nullable|string',
            'project_id'  => 'required|exists:projects,id',
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('global_role', 'employee');
                }),
            ],
            'status'      => 'required|in:todo,in_progress,review,done',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'required|date|after_or_equal:today',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Task created successfully',
            'data'    => $task,
        ], 201);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Task not found with ID: ' . $id,
            ], 404);
        }

        $task->load(['project', 'assignedTo', 'comments']);

        return response()->json([
            'status' => 'success',
            'data'   => $task,
        ]);
    }

    /**
     * Update task details
     * Supports partial updates via 'sometimes' validation
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'sometimes|required|string|min:2|max:255',
            'description' => 'nullable|string',
            'assigned_to' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('global_role', 'employee')),
            ],
            'status'      => 'sometimes|required|in:todo,in_progress,review,done',
            'priority'    => 'sometimes|required|in:low,medium,high',
            'due_date'    => 'sometimes|required|date',
        ]);

        $task->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data'   => $task->load(['assignedTo']),
        ]);
    }

    /**
     * Remove task from database
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Task deleted successfully',
        ]);
    }

    /**
     * Specific endpoint for Kanban drag-and-drop status changes
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task status updated',
            'data' => ['new_status' => $task->status]
        ]);
    }
}
