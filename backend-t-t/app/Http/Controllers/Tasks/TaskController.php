<?php 
namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskCreateRequest;
use App\Application\Services\Tasks\TaskService;

class TaskController extends Controller{


    public function __construct(private TaskService $taskService){}

    public function create(TaskCreateRequest $taskCreateRequest){

        return response()->json($this->taskService->create($taskCreateRequest));
    }
}