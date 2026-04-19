<?php

namespace App\Http\Requests\Tasks;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberDailyTaskStoreRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        if (! $this->filled('plan_date')) {
            $this->merge([
                'plan_date' => now()->toDateString(),
            ]);
        }
    }

    public function rules(): array
    {
        $team = $this->route('team');

        return [
            'task_id' => [
                'required',
                'uuid',
                Rule::exists('tasks', 'id')->where(
                    fn ($query) => $query->where('team_id', $team->id)
                ),
            ],
            'plan_date' => ['required', 'date'],
        ];
    }
}
