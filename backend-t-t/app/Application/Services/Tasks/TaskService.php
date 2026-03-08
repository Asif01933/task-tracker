<?php

namespace App\Application\Services\Tasks;

use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Models\TeamMember;

class TaskService
{

    public function __construct(
        private TaskRepositoryInterface $taskRepositoryInterface,
        private MemberRepositoryInterface $memberRepositoryInterface
    ) {}

    public function create($request, $team)
    {

        $validatedData = $request->validated();

        $teamMember = $this->memberRepositoryInterface->findTeamMember($team->id, $request->user()->id);

        if (!$teamMember) {
            throw new \Exception("You are not a member of this team");
        }
        $validatedData['team_member_id'] = $teamMember->id;
        $validatedData['team_id'] = $team->id;


        $task = $this->taskRepositoryInterface->create($validatedData);

        if (!$task) {
            throw new \Exception("Task is not created");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task created successfully',
            'data' => $task
        ];
    }

    public function update($request, $task, $team)
    {
        
        if (!$team || !$task) {
            throw new \Exception("Team or task not found");
        }
        if (!$team->teamMembers()->where('user_id', $request->user()->id)->exists()) {
            throw new \Exception("You are not a member of this team");
        }
        if (!$task->team_id == $team->id) {
            throw new \Exception("Task is not associated with this team");
        }

        $task = $this->taskRepositoryInterface->update($task, $request->validated());

        if (!$task) {
            throw new \Exception("Task update is not successful");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task updated successfully',
            'data' => $task
        ];
    }

    public function delete($team, $task)
    {
        if(!$team || !$task){
            throw new \Exception("Team or task not found");
        }
        if(!$team->teamMembers()->where('user_id', auth()->user()->id)->exists()){
            throw new \Exception("You are not a member of this team");
        }
        if(!$task->team_id == $team->id){
            throw new \Exception("Task is not associated with this team");
        }

        $this->taskRepositoryInterface->delete($task);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task deleted successfully'
        ];
    }

    public function tasks($request, $team)
    {


        if(!$team){
            throw new \Exception("Team not found");
        }

        if(!$team->teamMembers()->where('user_id', $request->user()->id)->exists()){
            throw new \Exception("You are not a member of this team");
        }


        $tasks = $this->taskRepositoryInterface->tasks([
            'user_id'    => $request->input('user_id'),
            'team_id'    => $team->id,
            'priority'   => $request->input('priority'),
            'status'     => $request->input('status'),
            'category'   => $request->input('category'),
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
        ]);

        $taskLists = [];

        foreach ($tasks as $task) {
            $taskLists[] = [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'category' => $task->category,
                'priority' => $task->priority,
                'status' => $task->status,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
                'assigned_to' => $task->teamMember->user->name,
            ];
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $taskLists
        ];
    }

    public function list($teamId)
    {
        $tasks = $this->taskRepositoryInterface->listByTeam($teamId);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ];
    }

    public function selfTasks()
    {
        $tasks = $this->taskRepositoryInterface->listByMember(auth()->user()->id);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ];
    }

    public function view($team, $task)
    {

        if (!$team || !$task) {
            throw new \Exception("Team or task not found");
        }

        if (!$team->teamMembers()->where('user_id', auth()->user()->id)->exists()) {
            throw new \Exception("You are not a member of this team");
        }

        if (!$task->team_id == $team->id) {
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
