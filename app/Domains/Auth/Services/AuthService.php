<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\DTOs\LoginDTO;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(LoginDTO $dto): array
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        if (!$user->active) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive.'],
            ]);
        }

        // Validación de 'Login por Conexión' para Transportistas (App Móvil)
        if ($user->hasRole('delivery')) {
            $this->ensureValidConnection($user, $dto->connection_id);
        }

        $token = $user->createToken($dto->device_name)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    protected function ensureValidConnection(User $user, ?string $connectionId): void
    {
        if (empty($connectionId)) {
            throw ValidationException::withMessages([
                'connection_id' => ['El identificador de conexión es obligatorio para transportistas.'],
            ]);
        }

        if (empty($user->connection_id)) {
            // Primer login del transportista: registramos su connection_id
            $user->update(['connection_id' => $connectionId]);
            return;
        }

        if ($user->connection_id !== $connectionId) {
            throw ValidationException::withMessages([
                'connection_id' => ['Este dispositivo no está autorizado para esta cuenta de transportista.'],
            ]);
        }
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
