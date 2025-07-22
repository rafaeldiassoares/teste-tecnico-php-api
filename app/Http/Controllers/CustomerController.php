<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Services\LogService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="Listar todos os clientes",
     *     tags={"Customers"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Customer")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            'status' => 200,
            'data' => $customers
        ]);
    }

    /**
     *  @OA\Post(
     *     path="/customers",
     *    summary="Criar um novo cliente",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Cliente criado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function store(StoreCustomerRequest $request)
    {
        $customers = new Customer();
        $customers->name = $request->input('name');
        $customers->email = $request->input('email');
        $customers->cpf = $request->input('cpf');
        $customers->save();

        return response()->json([
            'status' => 201,
            'message' => 'Cliente criado com sucesso',
            'data' => $customers
        ]);
    }


    /**
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *     path="/customers/{id}",
     *     summary="Exibir um cliente específico",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $customer =  Customer::findOrFail($id);
            return response()->json([
                'status' => 200,
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('showCustomer.failed', [
                'customerId' => $id,
                'reason' => 'not_found'
            ]);
            throw new NotFoundException('Cliente não encontrado');
        }
    }

    /**
     * @OA\Patch(
     *     path="/customers/{id}",
     *     summary="Atualizar um cliente específico",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Cliente atualizado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $customer =  Customer::find($id);

            if (!$customer) {
                throw new NotFoundException('Cliente não encontrado');
            }

            if($request->has('name')) {
                $customer->name = $request->input('name', $customer->name);
            }
            if($request->has('email')) {
                $customer->email = $request->input('email', $customer->email);
            }
            if($request->has('cpf')) {
                $customer->cpf = $request->input('cpf', $customer->cpf);
            }
            $customer->save();

            return response()->json([
                'status' => 200,
                'message' => 'Cliente atualizado com sucesso',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('updateCustomer.failed', [
                'customerId' => $id,
                'reason' => 'not_found'
            ]);

            return response()->json( [
                'status'=> 422,
                'message' => 'Erro de validação'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer =  Customer::findOrFail($id);
            $customer->delete();
            return response()->json([
                'status' => 204,
                'message' => 'Cliente deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('deleteCustomer.failed', [
                'customerId' => $id,
                'reason' => 'not_found'
            ]);

            throw new NotFoundException('Cliente não encontrado');
        }
    }
}
