<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers()
    {
        $auth = $this->authenticatedUser();
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers', $auth['headers']);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'cpf',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_customer()
    {
        $auth = $this->authenticatedUser();
        $customerData = [
            'name' => 'Maria Silva',
            'email' => 'maria@teste.com',
            'cpf' => '123.456.789-10'
        ];

        $response = $this->postJson('/api/customers', $customerData, $auth['headers']);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 201,
                    'message' => 'Cliente criado com sucesso'
                ])
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'cpf',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Maria Silva',
            'email' => 'maria@teste.com',
            'cpf' => '123.456.789-10'
        ]);
    }

    public function test_cannot_create_customer_with_invalid_data()
    {
        $auth = $this->authenticatedUser();
        $invalidData = [
            'name' => '', // obrigatório
            'email' => 'invalid-email', // formato inválido
            'cpf' => '' // obrigatório
        ];

        $response = $this->postJson('/api/customers', $invalidData, $auth['headers']);


        $response->assertStatus(422)
        ->assertJson([
            'status' => 422,
            'message' => 'Dados inválidos'
        ]);

    }

    public function test_cannot_create_customer_with_duplicate_email()
    {
        $auth = $this->authenticatedUser();
        $existingCustomer = Customer::factory()->create([
            'email' => 'teste@email.com'
        ]);

        $duplicateData = [
            'name' => 'João Silva',
            'email' => 'teste@email.com', // email já existe
            'cpf' => '987.654.321-00'
        ];

        $response = $this->postJson('/api/customers', $duplicateData, $auth['headers']);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_create_customer_with_duplicate_cpf()
    {
        $auth = $this->authenticatedUser();
        $existingCustomer = Customer::factory()->create([
            'cpf' => '123.456.789-10'
        ]);

        $duplicateData = [
            'name' => 'João Silva',
            'email' => 'joao@email.com',
            'cpf' => '123.456.789-10' // CPF já existe
        ];

        $response = $this->postJson('/api/customers', $duplicateData, $auth['headers']);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cpf']);
    }

    public function test_can_show_customer()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}", $auth['headers']);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 200,
                    'data' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'cpf' => $customer->cpf
                    ]
                ]);
    }

    public function test_returns_404_when_customer_not_found()
    {
        $auth = $this->authenticatedUser();

        $response = $this->getJson('/api/customers/999', $auth['headers']);

        $response->assertStatus(404)
                ->assertJson([
                    'status' => 404,
                    'message' => 'Cliente não encontrado'
                ]);
    }

    public function test_can_update_customer()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();

        $updateData = [
            'name' => 'Nome Atualizado',
            'email' => 'email_atualizado@teste.com',
            'cpf' => '999.888.777-66'
        ];

        $response = $this->patchJson("/api/customers/{$customer->id}", $updateData, $auth['headers']);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 200,
                    'message' => 'Cliente atualizado com sucesso',
                    'data' => [
                        'id' => $customer->id,
                        'name' => 'Nome Atualizado',
                        'email' => 'email_atualizado@teste.com',
                        'cpf' => '999.888.777-66'
                    ]
                ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Nome Atualizado',
            'email' => 'email_atualizado@teste.com',
            'cpf' => '999.888.777-66'
        ]);
    }

    public function test_can_delete_customer()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}", [], $auth['headers']);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id
        ]);
    }

}
