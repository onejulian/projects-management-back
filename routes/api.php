<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['cors'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['auth:sanctum', 'cors'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/others', [ProjectController::class, 'others'])->name('projects.others');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/get-tasks/{id}', [ProjectController::class, 'getTasks'])->name('projects.getTasks');
    Route::put('/projects', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}/{project_id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}/{project_id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{id}/{project_id}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{project_id}', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::delete('/assignments/{id}/{project_id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
});
