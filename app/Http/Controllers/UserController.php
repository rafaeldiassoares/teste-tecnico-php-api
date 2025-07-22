<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Listar todos os usuários",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users =  User::all();
        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Criar um novo usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Usuário criado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Destrinchei a resposta dessa forma para padronizar com o retorno da API node que ficou nesse padrão
        // e facilitar a integração com o front-end.
        return response()->json([
            'status' => 201,
            'message' => 'Usuário criado com sucesso',
            'data' => $user
        ]);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Exibir um usuário específico",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function show(string $id)
    {
        $user =  User::find($id);

        if (!$user) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('showUser.failed', [
                'userId' => $id,
                'reason' => 'not_found'
            ]);

            throw new NotFoundException('Usuário não encontrado');
        }

        return response()->json([
            'status' => 200,
            'data' => $user
        ]);

    }

    /**
     * @OA\Patch(
     *     path="/users/{id}",
     *     summary="Atualizar um usuário específico",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Usuário atualizado com sucesso"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->input('name', $user->name);
            $user->email = $request->input('email', $user->email);
            if ($request->has('password')) {
                $user->password = bcrypt($request->input('password'));
            }
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Usuário atualizado com sucesso',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('updateUser.failed', [
                'userId' => $id,
                'reason' => 'not_found'
            ]);
            throw new NotFoundException('Usuário não encontrado');
        }
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Deletar um usuário específico",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuário deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 204,
                'message' => 'Usuário deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('deleteUser.failed', [
                'userId' => $id,
                'reason' => 'not_found'
            ]);
            throw new NotFoundException('Usuário não encontrado');
        }
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login do usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Login realizado com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="string", example="123"),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="jwt.token.here")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            // Exemplo de implementação de logs
            LogService::logAuthentication('login.failed', [
                'email' => $request->email,
                'reason' => 'invalid_credentials'
            ]);

            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ]

        ]);
    }
}
