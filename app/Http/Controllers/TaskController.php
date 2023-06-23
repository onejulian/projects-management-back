<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Assignment;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $project_id = $request->input('project_id');

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $task = new Task();
        $task->project_id = $project_id;
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->state = 'pending';
        $task->save();

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, string $project_id)
    {
        $user = auth()->user();

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        $assignments = Assignment::where('user_id', $user->id)->where('project_id', $project_id)->first();
        if (!$project && !$assignments) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $task = Task::where('project_id', $project_id)->where('id', $id)->first();
        if (!$task) {
            return response()->json([
                'message' => 'La tarea no se encuentra.'
            ], 400);
        }

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $id = $request->input('id');
        $project_id = $request->input('project_id');

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        $assignments = Assignment::where('user_id', $user->id)->where('project_id', $project_id)->first();
        if (!$project && !$assignments) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $task = Task::where('project_id', $project_id)->where('id', $id)->first();
        if (!$task) {
            return response()->json([
                'message' => 'La tarea no se encuentra.'
            ], 400);
        }

        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->state = $request->input('state');
        $task->save();

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $project_id)
    {
        $user = auth()->user();

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $task = Task::where('project_id', $project_id)->where('id', $id)->first();
        if (!$task) {
            return response()->json([
                'message' => 'La tarea no se encuentra.'
            ], 400);
        }

        $task->delete();

        return response()->json([
            'message' => 'La tarea ha sido eliminada.'
        ], 200);
    }
}
