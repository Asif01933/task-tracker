<?php
namespace App\Application\Services\Misc;

use App\Domain\Interfaces\LabelRepositoryInterface;
use App\Models\Label;
use App\Models\Task;
use App\Models\Team;

class LabelService
{
    public function __construct(private LabelRepositoryInterface $labelRepository){}

    public function store($request, $team)
    {
        $label = $this->labelRepository->store($request->validated(), $team);
        if (!$label) {
            throw new \Exception("Label is not created");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label created successfully',
            'data' => $label,
        ];
    }

    public function index($team)
    {
        $labels = $this->labelRepository->index($team);
        if (!$labels) {
            throw new \Exception("Labels are not found");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Labels fetched successfully',
            'data' => $labels,
        ];
    }

    public function update($request, Team $team, Label $label)
    {
        $this->authorizeTeamMember($team, $request->user()->id);

        if ($label->team_id !== $team->id) {
            throw new \Exception("Label does not belong to this team");
        }

        $label = $this->labelRepository->update($label, $request->validated());

        if (!$label) {
            throw new \Exception("Label update failed");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label updated successfully',
            'data' => $label,
        ];
    }

    public function destroy(Team $team, Label $label)
    {
        $this->authorizeTeamMember($team, auth()->user()->id);

        if ($label->team_id !== $team->id) {
            throw new \Exception("Label does not belong to this team");
        }

        $this->labelRepository->delete($label);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label deleted successfully',
        ];
    }

    public function attach($request, Team $team, Task $task)
    {
        $this->authorizeTeamMember($team, $request->user()->id);

        if ($task->team_id !== $team->id) {
            throw new \Exception("Task does not belong to this team");
        }

        $label = $this->labelRepository->findById($team, $request->validated()['label_id']);
        if (!$label) {
            throw new \Exception("Label not found for this team");
        }

        $labels = $this->labelRepository->attachLabelToTask($task, $label->id);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label attached to task successfully',
            'data' => $labels,
        ];
    }

    public function detach(Team $team, Task $task, Label $label)
    {
        $this->authorizeTeamMember($team, auth()->user()->id);

        if ($task->team_id !== $team->id) {
            throw new \Exception("Task does not belong to this team");
        }

        if ($label->team_id !== $team->id) {
            throw new \Exception("Label does not belong to this team");
        }

        $labels = $this->labelRepository->detachLabelFromTask($task, $label->id);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Label detached from task successfully',
            'data' => $labels,
        ];
    }

    private function authorizeTeamMember(Team $team, int $userId): void
    {
        if (!$team->teamMembers()->where('user_id', $userId)->exists()) {
            throw new \Exception("You are not a member of this team");
        }
    }
}
