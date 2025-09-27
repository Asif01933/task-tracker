<?php 
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\TeamMembers\MemberController;

// --------------------
// Authentication Routes
// --------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// --------------------
// Profile Routes
// 
Route::get('/me', [MemberController::class, 'myProfile'])->middleware('auth:sanctum');
Route::patch('/me', [MemberController::class, 'myProfileUpdate'])->middleware('auth:sanctum');

// --------------------
// Team Routes
// 
Route::post('/team/create', [TeamController::class, 'create'])->middleware('auth:sanctum');
Route::post('/team/invite', [TeamController::class, 'invite'])->middleware('auth:sanctum');


//--------------------
// Task Routes
//
Route::post('/tasks', [TaskController::class, 'create'])->middleware('auth:sanctum');
Route::patch('/tasks/{task}', [TaskController::class, 'update'] )->middleware('auth:sanctum');
Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->middleware('auth:sanctum');


//-----
//Reports
///
Route::get('/reports/{team}/download', [ReportController::class, 'download'])->middleware('auth:sanctum');