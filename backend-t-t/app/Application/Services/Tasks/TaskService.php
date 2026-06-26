<?php

namespace App\Application\Services\Tasks;

use App\Domain\Interfaces\MemberRepositoryInterface;
use App\Domain\Interfaces\TaskRepositoryInterface;
use App\Models\MemberDailyTask;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

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
        $validatedData['assigned_to'] = $teamMember->id;
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
        if ((int) $task->team_id !== (int) $team->id) {
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
        if((int) $task->team_id !== (int) $team->id){
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
            'assigned_to' => $request->input('assigned_to'),
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
                'problem_description' => $task->problem_description,
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

        if ((int) $task->team_id !== (int) $team->id) {
            throw new \Exception("Task is not associated with this team");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task retrieved successfully',
            'data' => $task
        ];
    }

    public function storeMemberDailyTask(array $validated, Team $team, int $userId): array
    {
        $teamMember = $this->memberRepositoryInterface->findTeamMember($team->id, $userId);
        if (! $teamMember) {
            throw new \Exception('You are not a member of this team');
        }

        $planDate = Carbon::parse($validated['plan_date'])->toDateString();

        $task = $this->taskRepositoryInterface->findInTeam($validated['task_id'], $team->id);
        if (! $task) {
            throw new \Exception('Task not found on this team');
        }

        if ($this->taskRepositoryInterface->memberDailyTaskExistsForMemberTaskAndDate(
            $teamMember->id,
            $task->id,
            $planDate
        )) {
            throw ValidationException::withMessages([
                'task_id' => ['This task is already on your list for that day.'],
            ]);
        }

        $memberDailyTask = $this->taskRepositoryInterface->createMemberDailyTask([
            'team_member_id' => $teamMember->id,
            'task_id' => $task->id,
            'plan_date' => $planDate,
            'task_note' => $validated['task_note'] ?? null,
        ]);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Task added to your day successfully',
            'data' => $memberDailyTask,
        ];
    }

    public function listMemberDailyTasks(array $filters, Team $team, int $userId): array
    {
        $teamMember = $this->findCurrentTeamMember($team, $userId);

        $filters['plan_date'] = isset($filters['plan_date'])
            ? Carbon::parse($filters['plan_date'])->toDateString()
            : Carbon::today()->toDateString();

        $memberDailyTasks = $this->taskRepositoryInterface->listMemberDailyTasks(
            $teamMember->id,
            $filters
        );

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Daily tasks retrieved successfully',
            'data' => $memberDailyTasks,
        ];
    }

    public function updateMemberDailyTask(array $validated, Team $team, MemberDailyTask $memberDailyTask, int $userId): array
    {
        $teamMember = $this->findCurrentTeamMember($team, $userId);
        $this->authorizeMemberDailyTask($memberDailyTask, $teamMember->id, $team->id);

        $taskId = $validated['task_id'] ?? $memberDailyTask->task_id;
        $planDate = isset($validated['plan_date'])
            ? Carbon::parse($validated['plan_date'])->toDateString()
            : $memberDailyTask->plan_date->toDateString();

        $task = $this->taskRepositoryInterface->findInTeam($taskId, $team->id);
        if (! $task) {
            throw new \Exception('Task not found on this team');
        }

        if ($this->taskRepositoryInterface->memberDailyTaskExistsForMemberTaskAndDate(
            $teamMember->id,
            $taskId,
            $planDate,
            $memberDailyTask->id
        )) {
            throw ValidationException::withMessages([
                'task_id' => ['This task is already on your list for that day.'],
            ]);
        }

        $validated['plan_date'] = $planDate;

        $memberDailyTask = $this->taskRepositoryInterface->updateMemberDailyTask(
            $memberDailyTask,
            $validated
        );

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Daily task updated successfully',
            'data' => $memberDailyTask,
        ];
    }

    public function deleteMemberDailyTask(Team $team, MemberDailyTask $memberDailyTask, int $userId): array
    {
        $teamMember = $this->findCurrentTeamMember($team, $userId);
        $this->authorizeMemberDailyTask($memberDailyTask, $teamMember->id, $team->id);

        $this->taskRepositoryInterface->deleteMemberDailyTask($memberDailyTask);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Daily task removed successfully',
        ];
    }

    public function completeMemberDailyTask(Team $team, MemberDailyTask $memberDailyTask, int $userId): array
    {
        $teamMember = $this->findCurrentTeamMember($team, $userId);
        $this->authorizeMemberDailyTask($memberDailyTask, $teamMember->id, $team->id);

        $memberDailyTask = $this->taskRepositoryInterface->completeMemberDailyTask($memberDailyTask);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Daily task completed successfully',
            'data' => $memberDailyTask,
        ];
    }

    private function findCurrentTeamMember(Team $team, int $userId)
    {
        $teamMember = $this->memberRepositoryInterface->findTeamMember($team->id, $userId);

        if (! $teamMember) {
            throw new \Exception('You are not a member of this team');
        }

        return $teamMember;
    }

    private function authorizeMemberDailyTask(MemberDailyTask $memberDailyTask, string $teamMemberId, int $teamId): void
    {
        $memberDailyTask->loadMissing('task');

        if ($memberDailyTask->team_member_id !== $teamMemberId || (int) $memberDailyTask->task->team_id !== $teamId) {
            throw new \Exception('Daily task not found on this team');
        }
    }
}
