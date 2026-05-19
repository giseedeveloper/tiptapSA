<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkWaiterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employment_type' => ['required', 'string', 'in:permanent,temporary'],
            'linked_until' => ['required_if:employment_type,temporary', 'nullable', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employment_type.required' => 'Choose type: Long-term (permanent) or Fixed period (show-time).',
            'employment_type.in' => 'Type must be permanent or temporary.',
            'linked_until.required_if' => 'End date is required for temporary (show-time) waiters.',
            'linked_until.after_or_equal' => 'End date must be today or later.',
        ];
    }
}
