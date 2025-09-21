<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class TeamCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
        ];
    }


    /**
     * Return the validated data along with the owner_id.
     */
    public function validatedWithOwner(): array
    {
        return array_merge($this->validated(), [
            'owner_id' => auth()->id(),
        ]);
    }
}
