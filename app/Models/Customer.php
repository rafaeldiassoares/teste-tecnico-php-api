<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * @OA\Schema(
 *    schema="Customer",
 *    type="object",
 *    title="Customer",
 *    required={"id", "name", "email", "cpf"},
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID do cliente",
 *        example=1
 *    ),
 *    @OA\Property(
 *        property="name",
 *        type="string",
 *        description="Nome do cliente",
 *        example="Maria Oliveira"
 *    ),
 *    @OA\Property(
 *        property="email",
 *        type="string",
 *        format="email",
 *        description="Email do cliente",
 *        example="maria@email.com"
 *    ),
 *    @OA\Property(
 *        property="cpf",
 *        type="string",
 *        description="CPF do cliente",
 *        example="123.456.789-00"
 *    )
 * )
 */
class Customer extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'email', 'cpf'];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
