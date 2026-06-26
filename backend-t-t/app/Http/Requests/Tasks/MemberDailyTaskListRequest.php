<?php

namespace App\Http\Requests\Tasks;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class MemberDailyTaskListRequest extends FormRequest
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
        return [
            'plan_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:planned,completed'],
        ];
    }
}
