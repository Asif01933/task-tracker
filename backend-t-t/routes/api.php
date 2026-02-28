<?php 
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Invitation\InvitationController;
use App\Http\Controllers\Misc\LabelController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Reports\ReportReceiverController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\TeamMembers\MemberController;
use App\Http\Controllers\Teams\TeamController;
use Illuminate\Support\Facades\Route;

// --------------------
// Authentication Routes
// --------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google/login', [AuthController::class, 'googleLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// --------------------
// Profile Routes
// 
Route::get('/me', [MemberController::class, 'myProfile'])->middleware('auth:sanctum');
Route::patch('/me', [MemberController::class, 'myProfileUpdate'])->middleware('auth:sanctum');

// --------------------
// Team Routes
// 
Route::post('/teams', [TeamController::class, 'create'])->middleware('auth:sanctum');
Route::post('/teams/invite', [InvitationController::class, 'invite'])->middleware('auth:sanctum');
Route::post('/teams/invite/accept', [InvitationController::class, 'acceptInvitation'])->middleware('auth:sanctum');
Route::patch('/teams/{team}', [TeamController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/teams/{team}', [TeamController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/teams', [TeamController::class, 'myTeams'])->middleware('auth:sanctum');
Route::get('/teams/{team}', [TeamController::class, 'view'])->middleware('auth:sanctum');

//--------------------
// Task Routes
//
Route::post('/tasks', [TaskController::class, 'create'])->middleware('auth:sanctum');
Route::patch('/tasks/{task}', [TaskController::class, 'update'] )->middleware('auth:sanctum');
Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/tasks/team/{team}', [TaskController::class, 'list'])->middleware('auth:sanctum');
Route::get('/tasks/self', [TaskController::class, 'selfTasks'])->middleware('auth:sanctum');Route::get('/tasks', [TaskController::class, 'tasks'])->middleware('auth:sanctum');

//-----
//Reports
///
Route::get('/reports/{team}/download', [ReportController::class, 'download'])->middleware('auth:sanctum');
Route::post('/reports/{report}/send', [ReportController::class, 'send'])->middleware('auth:sanctum');
Route::post('/report-receivers', [ReportReceiverController::class, 'create'])->middleware('auth:sanctum');
Route::get('/report-receivers/{team}', [ReportReceiverController::class, 'view'])->middleware('auth:sanctum');
Route::patch('/report-receivers/{report_receiver}', [ReportReceiverController::class, 'update'])->middleware('auth:sanctum');


// -------------------------------------------------------------------------
    // Label routes (team-scoped)
    // -------------------------------------------------------------------------
Route::prefix('teams/{team}/labels')->middleware('auth:sanctum')->controller(LabelController::class)->group(function () {
    Route::get('/',          'index');    // GET    /api/teams/{team}/labels
    Route::post('/',         'store');    // POST   /api/teams/{team}/labels
    Route::put('/{label}',   'update');   // PUT    /api/teams/{team}/labels/{label}
    Route::delete('/{label}','destroy');  // DELETE /api/teams/{team}/labels/{label}
    });

    // -------------------------------------------------------------------------
    // Task label routes (attach / detach)
    // -------------------------------------------------------------------------
Route::prefix('tasks/{task}/labels')->middleware('auth:sanctum')->controller(LabelController::class)->group(function () {
    Route::post('/',             'attach');  // POST   /api/tasks/{task}/labels
    Route::delete('/{label}',    'detach');  // DELETE /api/tasks/{task}/labels/{label}
});