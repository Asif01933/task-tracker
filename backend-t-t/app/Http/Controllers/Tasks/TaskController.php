<?php 
namespace App\Http\Controllers\Tasks;

use App\Application\Services\Tasks\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskCreateRequest;
use App\Http\Requests\Tasks\TasksRequest;
use App\Http\Requests\Tasks\TaskUpdateRequest;
use App\Models\Task;
use App\Models\Team;

class TaskController extends Controller{


    public function __construct(private TaskService $taskService){}

    public function create(TaskCreateRequest $taskCreateRequest, Team $team){

        return response()->json($this->taskService->create($taskCreateRequest, $team));
    }

    public function update(TaskUpdateRequest $request,Team $team, Task $task){
        return response()->json($this->taskService->update($request, $task, $team));
    }

    public function delete(Team $team, Task $task){
        return response()->json($this->taskService->delete($team, $task));
    }

    public function tasks(TasksRequest $tasksRequest, Team $team){
        return response()->json($this->taskService->tasks($tasksRequest, $team));
    }

    public function list($teamId){
        
        return response()->json($this->taskService->list($teamId));
    }
    public function selfTasks(){
        return response()->json($this->taskService->selfTasks());
    }

    public function view(Team $team, Task $task){
        return response()->json($this->taskService->view($team, $task));
    }
}