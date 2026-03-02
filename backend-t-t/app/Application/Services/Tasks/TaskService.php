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
        $validatedData['team_member_id'] = $teamMember->id;

        $task = $this->taskRepositoryInterface->create($validatedData);

        if(!$task){
            throw new \Exception("Task is not created");
            
        }

        return [
            'status' => true,
            'code' => 200,
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

    public function tasks($request){

        return $this->taskRepositoryInterface->tasks($request->user_id, $request->end_date, $request->start_date);
    }

    public function list($teamId){
        $tasks = $this->taskRepositoryInterface->listByTeam($teamId);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ];
    }

    public function selfTasks(){
        $tasks = $this->taskRepositoryInterface->listByMember(auth()->user()->id);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ];
    }

    public function view($team, $task){

        if(!$team || !$task){
            throw new \Exception("Team or task not found");
        }

        if(!$team->teamMembers()->where('user_id', auth()->user()->id)->exists()){
            throw new \Exception("You are not a member of this team");
        }
        
        if(!$task->team_id == $team->id){
            throw new \Exception("Task is not associated with this team");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task retrieved successfully',
            'data' => $task
        ];
    }
}