<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ProjectTypeController;
use App\Http\Controllers\Admin\TaskTemplateController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Client\ProjectController as ClientProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\SetupPasswordController;

// ── Public Auth Routes ────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');


Route::post('/setup-password/verify', [SetupPasswordController::class, 'verify']);
Route::post('/setup-password', [SetupPasswordController::class, 'setup']);

// ── Admin Role Routes ─────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('projects', ProjectController::class);

        Route::apiResource('tasks', TaskController::class);
       

        Route::apiResource('project-types', ProjectTypeController::class);
        Route::apiResource('task-templates', TaskTemplateController::class);
    });

// ── Employee Role Routes ──────────────────────────────────
Route::middleware(['auth:sanctum', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/tasks', [EmployeeTaskController::class, 'index']);
        Route::patch('/tasks/{task}/status', [EmployeeTaskController::class, 'updateStatus']);
        Route::post('/tasks/{task}/comments', [EmployeeTaskController::class, 'addComment']);
    });

// ── Client Role Routes ────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/projects', [ClientProjectController::class, 'index']);
        Route::get('/projects/{project}', [ClientProjectController::class, 'show']);
        Route::post('/projects/{project}/comments', [ClientProjectController::class, 'addComment']);
    });
