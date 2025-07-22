<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar migrations automaticamente
        // $this->artisan('migrate:fresh');
    }

    /**
     * Criar um usuário autenticado para testes
     */
    protected function authenticatedUser($userData = [])
    {
        $user = User::factory()->create($userData);
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
            'headers' => [
                'Authorization' => "Bearer $token",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ];
    }

    /**
     * Headers básicos para API
     */
    protected function apiHeaders($token = null)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        return $headers;
    }
}
