<?php

namespace App\Http\Requests\Tasks;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Since the route is protected by auth:sanctum, any authenticated user is allowed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'task_id'     => 'required|exists:tasks,id',
            'category'    => 'sometimes|string|max:100',
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable',
            'priority'    => 'sometimes|in:low,medium,high',
            'status'      => 'sometimes|in:pending,in progress,completed',
            'team_id'     => 'required|exists:teams,id',
            'member_id' => 'sometimes|exists:team_members,id',
        ];
    }

    
}
