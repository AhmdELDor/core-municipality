<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExploreRequest extends FormRequest
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
            'desc' => 'sometimes|string',
            'category' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:promotion,post',
            'citizen_id' => 'sometimes|exists:users,id',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,svg|max:5120',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => 'sometimes|in:pending,approved',
        ];
    }
}
