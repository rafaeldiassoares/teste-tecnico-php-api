<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use App\Models\Customer;
use App\Services\LogService;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/customers/{customerId}/addresses",
     *     summary="Listar todos os endereços de um cliente",
     *     tags={"Customer Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="customerId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de endereços do cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Address")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $addresses = Address::all();
        return response()->json([
            'status' => 200,
            'data' => $addresses
        ]);
    }

    /**
     * @OA\Post(
     *     path="/customers/{customerId}/addresses",
     *     summary="Criar um novo endereço para um cliente",
     *     tags={"Customer Addresses"},
     *     @OA\Parameter(name="customerId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Endereço criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Endereço criado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/Address")
     *         )
     *     )
     * )
     */
    public function store(StoreAddressRequest $request, string $customerId) // StoreAddressRequest é responsável pela validação
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            throw new NotFoundException('Cliente não encontrado');
        }

        $address = new Address();
        $address->customer_id = $customerId;
        $address->address = $request->input('address');
        $address->number = $request->input('number');
        $address->complement = $request->input('complement');
        $address->zip_code = $request->input('zipcode');
        $address->save();

        return response()->json([
            'status' => 201,
            'message' => 'Endereço criado com sucesso',
            'data' => $address
        ]);
    }

    /**
     * @OA\Get(
     *     path="/customers/{customerId}/addresses/{id}",
     *     summary="Exibir um endereço específico de um cliente",
     *     tags={"Customer Addresses"},
     *     @OA\Parameter(name="customerId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Address")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado"
     *     )
     * )
     */
    public function show(string $customerId, string $id)
    {
        try {
            $address = Address::where('id', $id)->where('customer_id', $customerId)->firstOrFail();

            return response()->json([
                'status' => 200,
                'data' => $address
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('showAddress.failed', [
                'customerId' => $customerId,
                'addressId' => $id,
                'reason' => 'not_found'
            ]);

            throw new NotFoundException('Endereço não encontrado');
        }
    }

    /**
     * @OA\Patch(
     *     path="/customers/{customerId}/addresses/{id}",
     *     summary="Atualizar um endereço específico de um cliente",
     *     tags={"Customer Addresses"},
     *     @OA\Parameter(name="customerId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Endereço atualizado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/Address")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado"
     *     )
     * )
     */
    public function update(Request $request, string $customerId, string $id)
    {
        try {
            $address = Address::where('id', $id)->where('customer_id', $customerId)->first();

            if (!$address) {
                throw new NotFoundException('Endereço não encontrado');
            }

            $address->customer_id = $customerId;

            if($request->has('address')) {
                $address->address = $request->input('address');
            }

            if($request->has('number')) {
                $address->number = $request->input('number');
            }

            if($request->has('complement')) {
                $address->complement = $request->input('complement');
            }

            if($request->has('zipcode')) {
                $address->zip_code = $request->input('zipcode');
            }

            $address->save();

            return response()->json([
                'status' => 200,
                'message' => 'Endereço atualizado com sucesso',
                'data' => $address
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('updateAddress.failed', [
                'customerId' => $customerId,
                'addressId' => $id,
                'reason' => 'not_found'
            ]);

            throw new NotFoundException('Endereço não encontrado');
        }
    }

    /**
     * @OA\Delete(
     *     path="/customers/{customerId}/addresses/{id}",
     *     summary="Deletar um endereço específico de um cliente",
     *     tags={"Customer Addresses"},
     *     @OA\Parameter(name="customerId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=204,
     *         description="Endereço deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereço não encontrado"
     *     )
     * )
     */
    public function destroy(string $customerId, string $id)
    {
        try {
            $address = Address::where('id', $id)->where('customer_id', $customerId)->firstOrFail();
            $address->delete();
           return response()->json([
                'status' => 204,
                'message' => 'Endereço deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('deleteAddress.failed', [
                'customerId' => $customerId,
                'addressId' => $id,
                'reason' => 'not_found'
            ]);

            throw new NotFoundException('Endereço não encontrado');
        }
    }
}
