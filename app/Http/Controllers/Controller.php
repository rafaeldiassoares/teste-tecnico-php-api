<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Einstein API",
 *     version="1.0.0",
 *     description="API para gerenciamento de clientes e endereços",
 *     @OA\Contact(
 *         email="contato@einstein.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:5002/api",
 *     description="Servidor de desenvolvimento"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller extends BaseController
{
        use AuthorizesRequests, ValidatesRequests;

}
