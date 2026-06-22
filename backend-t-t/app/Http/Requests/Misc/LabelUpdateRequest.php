<?php

namespace App\Http\Requests\Misc;

use App\Models\Label;
use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LabelUpdateRequest extends FormRequest
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
        $label = $this->route('label');
        $teamId = $team?->id;
        $labelId = $label?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('labels', 'name')
                    ->where(fn ($query) => $query->where('team_id', $teamId))
                    ->ignore($labelId),
            ],
        ];
    }
}
