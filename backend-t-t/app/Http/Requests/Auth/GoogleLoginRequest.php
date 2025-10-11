<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow all users to make this request
    }

    public function rules(): array
    {
        return [
            'credentials'    => 'required|string',
        ];
    }

   
}
