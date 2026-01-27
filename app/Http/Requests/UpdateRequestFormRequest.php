<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'sometimes|array',
            'version' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
            'instructions' => 'nullable|string',
            'attachments_required' => 'nullable|array',
            'fee_amount' => 'sometimes|numeric|min:0',
            'allowed_file_types' => 'nullable|array',
        ];
    }
}
