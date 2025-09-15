<?php 
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

// --------------------
// Authentication Routes
// --------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
