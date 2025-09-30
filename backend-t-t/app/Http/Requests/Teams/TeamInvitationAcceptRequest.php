<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Team;
use App\Models\User;
class TeamInvitationAcceptRequest extends FormRequest
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
            'token' => ['required', 'string'],
        ];
    }

    


}
