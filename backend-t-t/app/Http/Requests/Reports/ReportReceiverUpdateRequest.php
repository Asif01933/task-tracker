<?php

namespace App\Http\Requests\Reports;

use App\Models\TeamMember;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReportReceiverUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:report_receivers,id',
            'email' => [
            'required',
            'email',
            Rule::unique('report_receivers', 'email')->ignore($this->report_receiver->id),
        ],
        ];
    }

    
}
