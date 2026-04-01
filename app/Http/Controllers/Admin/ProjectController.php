<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * List all projects
     */
    public function index()
    {
        $projects = Project::with(['client', 'projectType', 'members'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $projects,
        ]);
    }

    /**
     * Create a new project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|min:2|max:255',
            'description'       => 'required|string',
            'status'            => 'required|in:pending,in_progress,completed,on_hold',
            'client_id'         => 'required|exists:users,id',
            'project_type_id'   => 'required|exists:project_types,id',
            'start_date'        => 'required|date|after_or_equal:2020-01-01',
            'end_date'          => 'required|date|after:start_date',
        ]);

        $project = DB::transaction(function () use ($validated) {
            // Create the project
            $project = Project::create([
                'name'              => $validated['name'],
                'description'       => $validated['description'],
                'status'            => $validated['status'],
                'client_id'           => $validated['client_id'],
                'project_type_id'   => $validated['project_type_id'],
                'start_date'        => $validated['start_date'],
                'end_date'          => $validated['end_date'],
                'created_by'        => Auth::id(),
            ]);

            // TODO: Auto-generate tasks from templates
            // $this->generateTasksFromTemplate($project);

            return $project;
        });

        return response()->json([
            'status' => 'success',
            'data'   => $project->load(['client', 'projectType']),
        ], 201);
    }

    /**
     * Get single project
     */
    public function show($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Project not found with ID: ' . $id,
            ], 404);
        }

        $project->load(['client', 'projectType', 'members']);

        return response()->json([
            'status' => 'success',
            'data'   => $project,
        ]);
    }
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'              => 'sometimes|required|string|min:2|max:255',
            'description'       => 'sometimes|required|string',
            'status'            => 'sometimes|required|in:pending,in_progress,completed,on_hold',
            'client_id'         => 'sometimes|required|exists:users,id',
            'project_type_id'   => 'sometimes|required|exists:project_types,id',
            'start_date'        => 'sometimes|required|date',
            'end_date'          => 'sometimes|required|date|after:start_date',
        ]);

        $project->update($validated);

        return response()->json([
            'status' => 'success',
            'data'   => $project->load(['client', 'projectType']),
        ]);
    }

    /**
     * Delete project
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Project deleted successfully.',
        ]);
    }

    /**
     * Assign employee to project
     */
    public function assignEmployee(Request $request, Project $project)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'role'      => 'required|in:manager,developer,viewer', // Match your migration enum
        ]);

        $project->members()->attach($validated['member_id'], [
            'project_role' => $validated['role'], // Changed 'role' to 'project_role'
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'member assigned to project.',
        ]);
    }

    /**
     * Remove employee from project
     */
    public function removeMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
        ]);

        $project->members()->detach($validated['member_id']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Member removed from project.',
        ]);
    }
}
