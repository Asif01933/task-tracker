<?php 
namespace App\Application\Services\Tasks;

use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Models\TeamMember;

class TaskService{

    public function __construct(private TaskRepositoryInterface $taskRepositoryInterface,
    private MemberRepositoryInterface $memberRepositoryInterface){}

    public function create($request){

        $validatedData = $request->validated();

        $teamMember = $this->memberRepositoryInterface->findTeamMember($validatedData['team_id'],$request->user()->id);

        if(!$teamMember){
            throw new \Exception("You are not a member of this team");
            
        }
        $validatedData['member_id'] = $teamMember->id;

        $task = $this->taskRepositoryInterface->create($validatedData);

        if(!$task){
            throw new \Exception("Task is not created");
            
        }

        return [
            'status' => true,
            'code' => false,
            'message' => 'Task created successfully',
            'data' => $task
        ];


    }

    public function update($request, $task){
        
        $task = $this->taskRepositoryInterface->update($task, $request->validated());

        if(!$task){
            throw new \Exception("Task update is not successful");
            
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task updated successfully',
            'data' => $task
        ];
    }

    public function delete($task){
        
        $this->taskRepositoryInterface->delete($task);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task deleted successfully'
        ];
    }
}