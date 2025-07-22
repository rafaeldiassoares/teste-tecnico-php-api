<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public static function logUserAction(string $action, array $data = [])
    {
        Log::channel('api')->info('User Action', [
            'action' => $action,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public static function logError(string $message, \Throwable $exception, array $context = [])
    {
        Log::channel('api')->error($message, [
            'exception' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ],
            'context' => $context,
            'user_id' => auth()->id(),
            'request_url' => request()->fullUrl(),
            'request_method' => request()->method(),
        ]);
    }

    public static function logDatabaseQuery(string $query, array $bindings, float $time)
    {
        if ($time > 100) { // Log apenas queries lentas (>100ms)
            Log::channel('api')->warning('Slow Query', [
                'query' => $query,
                'bindings' => $bindings,
                'execution_time_ms' => $time,
                'user_id' => auth()->id(),
            ]);
        }
    }

    public static function logAuthentication(string $event, array $data = [])
    {
        Log::channel('auth')->info('Authentication Event', [
            'event' => $event,
            'user_id' => $data['user_id'] ?? null,
            'email' => $data['email'] ?? null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data,
        ]);
    }
}
