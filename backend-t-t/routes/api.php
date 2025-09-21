<?php 
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TeamMembers\MemberController;
use App\Http\Controllers\Teams\TeamController;

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
