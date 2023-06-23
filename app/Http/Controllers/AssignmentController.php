<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($project_id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $users = [];

        $assignments = Assignment::where('project_id', $project_id)->get();

        foreach ($assignments as $assignment) {
            $user = User::where('id', $assignment->user_id)->first();
            array_push($users, $user);
        }
        return response()->json($users, 200);
    }

    /**
     * Show an assignment.
     */
    public function show(string $id, $project_id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $assignment = Assignment::where('project_id', $project_id)->where('id', $id)->first();
        if (!$assignment) {
            return response()->json([
                'message' => 'La asignación no se encuentra.'
            ], 400);
        }

        return response()->json($assignment, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $user_to_assign = $request->input('user_id');
        if ($user->id == $user_to_assign) {
            return response()->json([
                'message' => 'No puedes asignarte a ti mismo.'
            ], 400);
        }
        $project_id = $request->input('project_id');

        $user_to_assign = User::where('id', $user_to_assign)->first();
        if (!$user_to_assign) {
            return response()->json([
                'message' => 'El usuario no se encuentra.'
            ], 400);
        }

        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        // check if the user is already assigned
        $assignment = Assignment::where('user_id', $user_to_assign->id)->where('project_id', $project_id)->first();
        if ($assignment) {
            return response()->json([
                'message' => 'El usuario ya se encuentra asignado.'
            ], 400);
        }

        $assignment = new Assignment();
        $assignment->user_id = $request->input('user_id');
        $assignment->project_id = $project_id;
        $assignment->save();

        return response()->json($assignment, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, $project_id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->where('id', $project_id)->first();
        if (!$project) {
            return response()->json([
                'message' => 'El proyecto no se encuentra.'
            ], 400);
        }

        $assignment = Assignment::where('project_id', $project_id)->where('id', $id)->first();
        if (!$assignment) {
            return response()->json([
                'message' => 'La asignación no se encuentra.'
            ], 400);
        }

        $assignment->delete();

        return response()->json([
            'message' => 'La asignación ha sido eliminada.'
        ], 200);
    }
}
