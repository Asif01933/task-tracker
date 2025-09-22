<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Team;
use App\Models\User;
class TeamInviteRequest extends FormRequest
{
    /** @var Team|null */
    public $team;
    /**
     * Determine if the user is authorized to make this request.
     * Since the route is protected by auth:sanctum, any authenticated user is allowed.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'email'   => ['required', 'email'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->team = Team::find($this->team_id);

            if (! $this->team) {
                $validator->errors()->add('team_id', 'The selected team is invalid.');
                return;
            }

            // Ensure user is team owner/admin
            if ($this->team->owner_id !== auth()->id()) {
                $validator->errors()->add('team_id', 'You are not authorized to invite members to this team.');
            }

            // Ensure email is not already in the team
            $user = User::where('email', $this->email)->first();
            if ($user && $this->team->members()->where('user_id', $user->id)->exists()) {
                $validator->errors()->add('email', 'This user is already part of the team.');
            }
        });
    }


}
