<?php 
namespace App\Http\Controllers\Tasks;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskCreateRequest;
use App\Http\Requests\Tasks\TaskUpdateRequest;
use App\Application\Services\Tasks\TaskService;

class TaskController extends Controller{


    public function __construct(private TaskService $taskService){}

    public function create(TaskCreateRequest $taskCreateRequest){

        return response()->json($this->taskService->create($taskCreateRequest));
    }

    public function update(TaskUpdateRequest $request, Task $task){
        return response()->json($this->taskService->update($request, $task));
    }

    public function delete(Task $task){
        return response()->json($this->taskService->delete($task));
    }
}