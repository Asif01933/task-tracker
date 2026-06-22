<?php

namespace App\Http\Requests\Misc;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LabelAttachRequest extends FormRequest
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
        $teamId = $team?->id;

        return [
            'label_id' => [
                'required',
                'uuid',
                Rule::exists('labels', 'id')->where(fn ($query) => $query->where('team_id', $teamId)),
            ],
        ];
    }
}
