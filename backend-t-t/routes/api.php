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
Route::patch('/teams/{team}', [TeamController::class, 'update'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');
Route::delete('/teams/{team}', [TeamController::class, 'delete'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');
Route::get('/teams', [TeamController::class, 'myTeams'])->middleware('auth:sanctum');
Route::get('/teams/{team}', [TeamController::class, 'view'])
    ->middleware('auth:sanctum', 'team.context');
Route::get('/teams/{team}/members', [TeamController::class, 'members'])
    ->middleware('auth:sanctum', 'team.context');


/**
 * * Team member controlling
*/
//below route will be responsible for changing the role of the team member and also for removing the team member from the team
Route::patch('/teams/{team}/members/{member}', [MemberController::class, 'update'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');
Route::delete('/teams/{team}/members/{member}', [MemberController::class, 'remove'])
    ->middleware('auth:sanctum', 'team.context', 'team.role:owner,admin');
//--------------------
// Task Routes
//
Route::post('/teams/{team}/tasks', [TaskController::class, 'create'])->middleware('auth:sanctum');
Route::patch('/teams/{team}/tasks/{task}', [TaskController::class, 'update'] )->middleware('auth:sanctum');
Route::delete('/teams/{team}/tasks/{task}', [TaskController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/teams/{team}/tasks', [TaskController::class, 'tasks'])
    ->middleware('auth:sanctum', 'team.context');
Route::get('/tasks/self', [TaskController::class, 'selfTasks'])->middleware('auth:sanctum');

Route::get('/teams/{team}/tasks/{task}', [TaskController::class, 'view'])
    ->middleware('auth:sanctum', 'team.context');
//-----
//Reports
///
Route::get('/reports/{team}/download', [ReportController::class, 'download'])
    ->middleware('auth:sanctum', 'team.context');
Route::post('/reports/{report}/send', [ReportController::class, 'send'])->middleware('auth:sanctum');
Route::post('/report-receivers', [ReportReceiverController::class, 'create'])->middleware('auth:sanctum');
Route::get('/report-receivers/{team}', [ReportReceiverController::class, 'view'])
    ->middleware('auth:sanctum', 'team.context');
Route::patch('/report-receivers/{report_receiver}', [ReportReceiverController::class, 'update'])->middleware('auth:sanctum');


// -------------------------------------------------------------------------
    // Label routes (team-scoped)
    // -------------------------------------------------------------------------
Route::prefix('teams/{team}/labels')->middleware('auth:sanctum', 'team.context')->controller(LabelController::class)->group(function () {
    Route::get('/',          'index');    // GET    /api/teams/{team}/labels
    Route::post('/',         'store');    // POST   /api/teams/{team}/labels
    Route::put('/{label}',   'update');   // PUT    /api/teams/{team}/labels/{label}
    Route::delete('/{label}','destroy');  // DELETE /api/teams/{team}/labels/{label}
    });

    // -------------------------------------------------------------------------
    // Task label routes (attach / detach)
    // -------------------------------------------------------------------------
Route::prefix('teams/{team}/tasks/{task}/labels')->middleware('auth:sanctum')->controller(LabelController::class)->group(function () {
    Route::post('/',             'attach');  // POST   /api/tasks/{task}/labels
    Route::delete('/{label}',    'detach');  // DELETE /api/tasks/{task}/labels/{label}
});

//below route is for role is responsible for getting all the roles.
