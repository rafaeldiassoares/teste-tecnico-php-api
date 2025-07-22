<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users()
    {
        $auth = $this->authenticatedUser();
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users', $auth['headers']);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_can_create_user()
    {
        $auth = $this->authenticatedUser();

        $userData = [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData, $auth['headers']);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 201,
                    'message' => 'Usuário criado com sucesso'
                ]);
    }

    public function test_cannot_create_user_with_invalid_data()
    {
        $auth = $this->authenticatedUser();

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123' // passando senha inválida por ser muito curta
        ];

        $response = $this->postJson('/api/users', $invalidData, $auth['headers']);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_can_show_user()
    {
        $auth = $this->authenticatedUser();
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}", $auth['headers']);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 200,
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]);
    }

    public function test_returns_404_when_user_not_found()
    {
        $auth = $this->authenticatedUser();

        $response = $this->getJson('/api/users/999', $auth['headers']);

        $response->assertStatus(404)
                ->assertJson([
                    'status' => 404,
                    'message' => 'Usuário não encontrado'
                ]);
    }

    public function test_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users/login', $credentials, $this->apiHeaders());

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'user' => [
                            'id',
                            'name',
                            'email'
                        ],
                        'token'
                    ]
                ]);
    }

}
