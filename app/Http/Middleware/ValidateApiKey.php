<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * The expected API key (can also be read from env: config('app.api_key'))
     */
    protected string $validApiKey = '123456rx-ecourier123456';

    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('apiKey') ?? $request->header('api-key') ?? $request->header('Api-Key');

        if (empty($apiKey) || $apiKey !== $this->validApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
