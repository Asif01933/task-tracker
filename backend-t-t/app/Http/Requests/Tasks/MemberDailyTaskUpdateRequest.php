<?php

namespace App\Http\Requests\Tasks;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberDailyTaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $team = $this->route('team');
        $user = $this->user();

        if (! $team || ! $user) {
            return false;
        }

        return TeamMember::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function rules(): array
    {
        $team = $this->route('team');

        return [
            'task_id' => [
                'sometimes',
                'uuid',
                Rule::exists('tasks', 'id')->where(
                    fn ($query) => $query->where('team_id', $team->id)
                ),
            ],
            'plan_date' => ['sometimes', 'date'],
            'task_note' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
