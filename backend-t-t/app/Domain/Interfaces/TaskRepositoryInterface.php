<?php 
namespace App\Domain\Interfaces;


interface TaskRepositoryInterface{
    public function create(array $data);
    public function update($task, array $data);
    public function delete($task);
    public function tasks(array $filters = []);
    public function getTasksByRange($startDate, $endDate, $teamId, $memberId);
    public function listByTeam($teamId);
    public function listByMember($memberId);

    public function findInTeam($taskId, $teamId);

    public function memberDailyTaskExistsForMemberTaskAndDate($teamMemberId, $taskId, $planDate, $exceptId = null);

    public function createMemberDailyTask(array $data);

    public function listMemberDailyTasks($teamMemberId, array $filters = []);

    public function updateMemberDailyTask($memberDailyTask, array $data);

    public function deleteMemberDailyTask($memberDailyTask);

    public function completeMemberDailyTask($memberDailyTask);
}
