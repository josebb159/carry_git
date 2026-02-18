<?php

namespace App\Domains\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => ['required', 'string', 'exists:roles,name'],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
        } else {
            $rules['password'] = ['nullable', 'confirmed', 'min:8'];
        }

        return $rules;
    }
}
