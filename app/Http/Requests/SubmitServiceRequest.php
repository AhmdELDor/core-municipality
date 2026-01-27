<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitServiceRequest extends FormRequest
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
        $rules = [
            'request_form_id' => 'required|exists:request_forms,id',
            // 'data' might come as a JSON string in multipart/form-data, so we just check it's present
            'data' => 'required',
        ];

        // Get the request form to validate file fields dynamically
        if ($this->has('request_form_id')) {
            $requestForm = \App\Models\RequestForm::find($this->input('request_form_id'));

            if ($requestForm && isset($requestForm->fields)) {
                $fileFields = collect($requestForm->fields)->filter(function ($field) {
                    return isset($field['type']) && $field['type'] === 'file';
                });

                foreach ($fileFields as $field) {
                    $fieldName = $field['name'] ?? null;
                    if (!$fieldName) continue;

                    $isRequired = $field['required'] ?? false;
                    $allowMultiple = $field['multiple'] ?? false;

                    // Build validation rules for this file field
                    if ($allowMultiple) {
                        $rules[$fieldName] = ($isRequired ? 'required' : 'nullable') . '|array';
                        $rules[$fieldName . '.*'] = 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240';
                    } else {
                        $rules[$fieldName] = ($isRequired ? 'required' : 'nullable') . '|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240';
                    }
                }
            }
        }

        return $rules;
    }
}
