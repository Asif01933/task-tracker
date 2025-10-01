<?php

namespace App\Http\Requests\Teams;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class TeamUpdateRequest extends FormRequest
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
            'name'    => ['sometimes|string'],
            'frequency'   => ['sometimes|integer'],
        ];
    }

    
}
