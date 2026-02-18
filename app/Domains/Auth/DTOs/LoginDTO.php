<?php

namespace App\Domains\Auth\DTOs;

readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $device_name,
        public ?string $connection_id = null,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password'),
            device_name: $request->input('device_name', 'web'),
            connection_id: $request->input('connection_id')
        );
    }
}
