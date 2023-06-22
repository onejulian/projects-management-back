<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Assignment;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)->get();
        return response()->json($projects);
    }

    /**
     * Display others projects.
     */
    public function others()
    {
        $user = auth()->user();
        $assignments = Assignment::where('user_id', $user->id)->get();
        $projects = [];
        foreach ($assignments as $assignment) {
            $project = Project::where('id', $assignment->project_id)->first();
            array_push($projects, $project);
        }
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $project = new Project();
        $project->user_id = $user->id;
        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->date_init = $request->input('date_init');
        $project->date_end = $request->input('date_end');
        $project->save();

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $assignments = Assignment::where('user_id', $user->id)->where('project_id', $id)->first();    
        $project = Project::where('user_id', $user->id)->where('id', $id)->first();
        if (!$project && !$assignments) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        if ($assignments) {
            $project = Project::where('id', $id)->first();
        }

        return response()->json($project, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $project_id = $request->input('project_id');

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $project->title = $request->input('title');
        $project->description = $request->input('description');
        $project->date_init = $request->input('date_init');
        $project->date_end = $request->input('date_end');
        $project->save();

        return response()->json($project, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->where('id', $id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado exitosamente.'
        ], 200);
    }

    /**
     * Get tasks from a project.
     */
    public function getTasks(string $id)
    {
        $user = auth()->user();
        $assignments = Assignment::where('user_id', $user->id)->where('project_id', $id)->first();    
        $project = Project::where('user_id', $user->id)->where('id', $id)->first();

        if (!$project && !$assignments) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        if ($assignments) {
            $project = Project::where('id', $id)->first();
        }

        $tasks = $project->tasks()->get();

        return response()->json($tasks, 200);
    }
}
