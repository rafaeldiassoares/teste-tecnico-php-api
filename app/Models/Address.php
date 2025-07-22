<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Address",
 *     type="object",
 *     title="Address",
 *     required={"customer_id", "address", "number", "zip_code"},
 *     @OA\Property(
 *         property="customer_id",
 *         type="integer",
 *         description="ID do cliente",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Endereço",
 *         example="Rua das Flores"
 *     ),
 *     @OA\Property(
 *         property="number",
 *         type="string",
 *         description="Número",
 *         example="123"
 *     ),
 *     @OA\Property(
 *         property="complement",
 *         type="string",
 *         description="Complemento",
 *         example="Apto 456"
 *     ),
 *     @OA\Property(
 *         property="zip_code",
 *         type="string",
 *         description="CEP",
 *         example="12345-678"
 *     )
 * )
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'address', 'number', 'complement', 'zip_code'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
