<?php

namespace App\Domains\Events\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Shared\Enums\EventType;

class CreateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(EventType::class)],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'array'],
            'location.lat' => ['nullable', 'numeric'],
            'location.lng' => ['nullable', 'numeric'],
            'location.address' => ['nullable', 'string'],
            'meta_data' => ['nullable', 'array'],
        ];
    }
}
