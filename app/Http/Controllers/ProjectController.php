<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $projects = Project::all();
        
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($project, 201);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
