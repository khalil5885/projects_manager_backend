<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectType;
use Illuminate\Support\Facades\DB;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $projectTypes = ProjectType::with(['projects', 'taskTemplates'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $projectTypes,
        ]);
    }
    public function store(Request $request, $projectType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:project_types,name,' . $projectType->id . '|max:255',

            'description'       => 'nullable|string',
        ]);

        $projectType = ProjectType::create($validated);




        return response()->json([
            'status' => 'success',
            'data'   => $projectType,
        ], 201);
    }
    //get single project
    public function show(ProjectType $projectType)
    {
        $projectType->load('taskTemplates', 'projects');
        return response()->json([
            'status' => 'success',
            'data'   => $projectType,
        ]);
    }
    public function destroy(ProjectType $projectType)
    {
        if ($projectType->projects()->count() > 0) {
            return response()->json([
                'message' => 'cannot delete project type that has projects',

            ]);
        };
        $projectType->delete();
        return response()->json([
            'status' => 'success',
            'data' => 'project Type deleted successfully.',
        ]);
    }
    public function update(Request $request, ProjectType $projectType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|unique:project_Types,name' . $projectType->id . '|max:255',
            'description' => 'nullable|string',
        ]);
        $projectType->update($validated);
        return response()->json([
            'status' => 'success',
            'data' => $projectType,
        ]);
    }
}
