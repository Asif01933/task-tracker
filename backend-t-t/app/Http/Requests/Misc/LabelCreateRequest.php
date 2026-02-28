<?php

namespace App\Http\Requests\Misc;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LabelCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only members of the given team can create labels.
     */
    public function authorize(): bool
    {
        $team = $this->route('team');
        $user = $this->user();

        if (!$team || !$user) {
            return false;
        }

        return TeamMember::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $team = $this->route('team');
        $teamId = $team?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('labels', 'name')->where(fn ($query) => $query->where('team_id', $teamId)),
            ]
        ];
    }
}

