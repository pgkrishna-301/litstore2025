<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskManagement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskManagementController extends Controller
{
    // Show all tasks with user information
    public function index()
    {
        return TaskManagement::with('user')->get();
    }

    // Create a new task
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'assignment_to' => 'nullable|string',
            'assignment_by' => 'nullable|string',
            'due_date' => 'nullable|string',
            'start_date' => 'nullable|string',
            'importance_scale' => 'nullable|string',
            'status' => 'nullable|string',
            'reminder_set' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:user,id',
        ]);

        // Optional: log for debugging
        \Log::info('Task creation data:', $data);

        return TaskManagement::create($data);
    }

    public function getByUser($userId)
    {
        // Validate that the user exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return TaskManagement::where('user_id', $userId)->with('user')->get();
    }

    // Show one task with user information
    public function show($id)
    {
        return TaskManagement::with('user')->findOrFail($id);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'assignment_to' => 'nullable|string',
            'assignment_by' => 'nullable|string',
            'due_date' => 'nullable|string',
            'start_date' => 'nullable|string',
            'importance_scale' => 'nullable|string',
            'status' => 'nullable|string',
            'reminder_set' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:user,id',
        ]);

        $task = TaskManagement::findOrFail($id);
        $task->update($request->all());
        return $task->load('user');
    }

    // Delete a task
    public function destroy($id)
    {
        TaskManagement::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    // Test method to verify foreign key relationship
    public function testRelationship()
    {
        try {
            // Get a task with its user
            $task = TaskManagement::with('user')->first();
            
            if (!$task) {
                return response()->json(['message' => 'No tasks found'], 404);
            }

            return response()->json([
                'message' => 'Foreign key relationship working correctly',
                'task' => $task,
                'user' => $task->user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error testing relationship',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
