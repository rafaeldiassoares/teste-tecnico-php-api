<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerAddressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customer_addresses()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();
        Address::factory()->count(2)->create(['customer_id' => $customer->id]);

        $response = $this->getJson("/api/customers/{$customer->id}/addresses", $auth['headers']);

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data')
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'id',
                            'customer_id',
                            'address',
                            'number',
                            'complement',
                            'zip_code',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    public function test_can_create_address_for_customer()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();

        $addressData = [
            'address' => 'Rua das Flores',
            'number' => '123',
            'complement' => 'Apto 101',
            'zipcode' => '12345-678'
        ];

        $response = $this->postJson(
            "/api/customers/{$customer->id}/addresses",
            $addressData,
            $auth['headers']
        );

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 201,
                    'message' => 'Endereço criado com sucesso'
                ]);

        $this->assertDatabaseHas('addresses', [
            'customer_id' => $customer->id,
            'address' => 'Rua das Flores',
            'number' => '123',
            'zip_code' => '12345-678'
        ]);
    }

    public function test_cannot_create_address_for_nonexistent_customer()
    {
        $auth = $this->authenticatedUser();
        $addressData = [
            'address' => 'Rua das Flores',
            'number' => '123',
            'zipcode' => '12345-678'
        ];

        $response = $this->postJson('/api/customers/999/addresses', $addressData, $auth['headers']);

        $response->assertStatus(404);
    }

    public function test_cannot_create_address_with_invalid_data()
    {
        $auth = $this->authenticatedUser();
        $customer = Customer::factory()->create();

        $invalidData = [
            'address' => '', // obrigatório
            'zipcode' => ''  // obrigatório
        ];

        $response = $this->postJson(
            "/api/customers/{$customer->id}/addresses",
            $invalidData,
            $auth['headers']
        );

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['address', 'zipcode']);
    }

    public function test_requires_authentication()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}/addresses");

        $response->assertStatus(401);
    }
}
